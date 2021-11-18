<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use InvalidArgumentException;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class GIController extends Controller
{
    public function login(Request $request)
    {
        $request->session()->put("state", $state = Str::random(40));

        $query = http_build_query([
            'client_id' => config('auth.gi_id'),
            'redirect_uri' => config('auth.gi_callback'),
            'response_type' => 'code',
            'state' => $state,
        ]);

        return redirect(config('auth.gi_host') . '/oauth/authorize?' . $query);
    }

    public function callback(Request $request)
    {
        $state = $request->session()->pull('state');

        throw_unless(strlen($state) > 0 && $state == $request->state, 
            InvalidArgumentException::class);

        $response = Http::asForm()->post(
            config('auth.gi_host') . '/oauth/token',
        [
            'grant_type' => 'authorization_code',
            'client_id' => config('auth.gi_id'),
            'client_secret' => config('auth.gi_secret'),
            'redirect_uri' => config('auth.gi_callback'),
            'code' => $request->code,
        ]);

        $request->session()->put($response->json());
        return redirect()->route('guruinovatif.authuser');
    }

    public function authuser(Request $request)
    {
        $access_token = $request->session()->get('access_token');

        $response = Http::withHeaders([
            'Accept' => 'application/json',
            'Authorization' => "Bearer " . $access_token,
        ])->get(config('auth.gi_host') . '/api/v1/user');
        
        $attr = $response->json();

        try {
            $email = $attr['email'];
        } catch (\Throwable $th) {
            return redirect('/')->withErrors('Failed to get login information! Try again.');
        }


        $attr = [
            'name' => $attr['name'], 
            'email' => $attr['email'],
            'email_verified_at' => $attr['email_verified_at'],
            'access_token' => $access_token,
            'provider' => 'Guruinovatif',
            'provider_id' => $attr['id'],
            'avatar' => config('auth.gi_host') . '/' . $attr['image'],
        ];

        $account = User::where('email', $email)->first();

        if(!$account) {
            $check = ['email' => $email];
            User::firstOrCreate($check, $attr);
        } else {
            $account->update($attr);
        }

        Auth::login($account);
        return redirect('home');
    }
}
