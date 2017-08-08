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

Auth::routes();

Route::get('/home', 'User\UserController@index')->name('home');

Route::prefix('mocup')->group(function(){
	Route::get('home', function(){return view('layouts.home');});
	Route::get('bid-current', function(){return view('bid.bid_current');});
	Route::get('bid-done', function(){return view('bid.bid_done');});
	Route::get('bid-manager', function(){return view('user.bid-manager');});
	Route::get('user-info', function(){return view('user.info');});
});
