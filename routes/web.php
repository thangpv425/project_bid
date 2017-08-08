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
	Route::get('home', function(){return view('layouts.home');})->name('mocup-home');
	Route::get('bid-current', function(){return view('bid.bid_current');})->name('mocup-bid-current');
	Route::get('bid-done', function(){return view('bid.bid_done');})->name('mocup-bid-done');
	Route::get('bid-manager', function(){return view('user.bid-manager');})->name('mocup-bid-manager');
	Route::get('user-info', function(){return view('user.info');})->name('mocup-user-info');
	Route::get('change-email', function(){return view('user.change_email');})->name('mocup-change-email');
	Route::get('change-password', function(){return view('user.change_password');})->name('mocup-change-password');
	Route::get('delete-account', function(){return view('user.delete_account');})->name('mocup-delete-account');
	Route::get('list-mail', function(){return view('user.list_mail');})->name('mocup-list-mail');

});
