<?php

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\Auth\GIController;
use App\Http\Controllers\Auth\OauthController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);

Route::get('oauth/{driver}', [OauthController::class, 'redirectToProvider'])->name('social.oauth');
Route::get('oauth/{driver}/callback', [OauthController::class, 'handleProviderCallback'])->name('social.callback');

// GI Auth
Route::get('/guruinovatif/login', [GIController::class, 'login'])->name('guruinovatif.login');
Route::get('/guruinovatif/callback', [GIController::class, 'callback'])->name('guruinovatif.callback');
Route::get('/guruinovatif/authuser', [GIController::class, 'authuser'])->name('guruinovatif.authuser');

Route::get('/home', [HomeController::class, 'index'])->name('home');
