<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $access = Http::withToken(Auth::user()->access_token)->get(config('auth.gi_host') . '/api/v1/mycourse?limit=6');
        $mycourse = $access->json();

        return view('home', compact('mycourse'));
    }
}
