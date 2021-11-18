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
            'scope' => env('GI_SCOPES'),
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
        ])->get(config('auth.gi_host') . '/api/user');
        
        $attr = $response->json();

        try {
            $email = $attr['email'];
        } catch (\Throwable $th) {
            return redirect('/')->withErrors('Failed to get login information! Try again.');
        }

        $user = User::where('email', $email)->first();
        if(!$user) {
            $user = new User;
            $user->name = $attr['name'];
            $user->email = $attr['email'];
            $user->email_verified_at = $attr['email_verified_at'];
            $user->access_token = $access_token;
            $user->provider = 'Guruinovatif';
            $user->provider_id = $attr['id'];
            $user->avatar = config('auth.gi_host') . $attr['image'];
            $user->save();
        }

        Auth::login($user);
        return redirect('home');
    }
}
