<?php

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

Route::get('/dashboard', function () {
    return 'You are authorized to see this page';
})->middleware('auth');

Route::get('/dashboard', 'DashboardController@index');

Route::get('/auth/github', 'Auth\LoginController@redirectToServiceProvider')
    ->name('login');
Route::get('/auth/github/callback', 'Auth\LoginController@handleProviderCallback');
Route::get('/logout', function () {
    Auth::logout();

    return redirect('/');
});