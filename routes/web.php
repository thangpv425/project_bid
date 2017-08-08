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

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register', 'Auth\RegisterController@showRegistrationForm')->name('register');
Route::post('register', 'Auth\RegisterController@register');

Route::get('/home', 'User\UserController@index')->name('home');
Route::get('password/reset/{user_id}/{token}/{token_type}', 'Auth\ResetPasswordController@showResetForm')
    ->name('password.reset');
Route::post('password/email', 'Auth\ForgotPasswordController@sendMail')->name('password.email');
Route::get('password/reset', 'Auth\ForgotPasswordController@show')->name('password.request');
Route::post('password/reset', 'Auth\ResetPasswordController@reset');
Route::get('password/reset/{hash_key}', 'Auth\ResetPasswordController@showResetForm')
    ->name('password.reset');

Route::prefix('mocup')->group(function(){
	Route::get('home', function(){return view('layouts.home');});
	Route::get('bid-current', function(){return view('bid.bid_current');});
	Route::get('bid-done', function(){return view('bid.bid_done');});
	Route::get('bid-manager', function(){return view('user.bid-manager');});
	Route::get('user-info', function(){return view('user.info');});
});
