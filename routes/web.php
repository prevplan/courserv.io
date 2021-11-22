<?php

use Illuminate\Support\Facades\Route;

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

Route::group(
    [
        'prefix' => LaravelLocalization::setLocale(),
        'middleware' => [ 'localeSessionRedirect', 'localizationRedirect', 'localeViewPath', '\App\Http\Middleware\Localization' ]
    ], function()
{
    Route::get('/', function () {
        return view('welcome');
    });

    Route::get('login', \App\Http\Livewire\Auth\Login::class)
        ->middleware('guest')
        ->name('login');

    Route::get('home', \App\Http\Livewire\Home::class)
        ->middleware('auth')
        ->name('home');
});
