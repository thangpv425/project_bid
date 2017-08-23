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

Route::get('/', 'HomeController@index')->name('home');
Route::get('/current-bids', 'HomeController@currentBids')->name('current-bids');
Route::get('/success-bids', 'HomeController@successBids')->name('success-bids');

Route::get('login', 'Auth\LoginController@show')->name('login');
Route::post('login', 'Auth\LoginController@login');

Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('register', 'Auth\RegisterController@show')->name('register');
Route::post('register', 'Auth\RegisterController@register');
Route::get('active_user/{hash_key}', 'Auth\RegisterController@active')->name('register.active');

Route::post('password/email', 'Auth\ForgotPasswordController@sendMail')->name('password.email');
Route::get('password/reset', 'Auth\ForgotPasswordController@show')->name('password.request');
Route::post('password/reset', 'Auth\ForgotPasswordController@forgot');
Route::get('password/reset/{hash_key}', 'Auth\ForgotPasswordController@showResetForm')
    ->name('password.reset');

Route::prefix('user')->group(function(){
    Route::get('change-password', 'User\UserController@showChangePassword');
    Route::post('change-password', 'User\UserController@changePassword')->name('user.change-password');
    Route::get('change-email', 'User\UserController@showChangeEmail');
    Route::post('change-email', 'User\UserController@changeEmail')->name('user.change-email');
    Route::get('change-email/{hash_key}', 'User\UserController@confirmChangeEmail')->name('user.confirm.change-email');
    Route::get('delete-account', 'User\UserController@deleteUser');
    Route::post('delete-account', 'User\UserController@deleteUserProcess')->name('user.inactive');
    Route::get('profile', 'User\UserController@showProfile')->name('user.profile');
    Route::get('joining-bids', 'User\UserController@getJoiningBids')->name('user.joining-bids');
    Route::get('fail-bids', 'User\UserController@getFailBids')->name('user.fail-bids');
    Route::get('paying-bids', 'User\UserController@getUnpaidBids')->name('user.paying-bids');
    Route::get('paid-bids', 'User\UserController@getPaidBids')->name('user.paid-bids');
    Route::get('cancel-bids', 'User\UserController@getCanceledBids')->name('user.cancel-bids');
});

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

Route::prefix('admin')->group(function(){
	Route::get('/', function(){return view('admin.index');})->name('admin');
	Route::get('login', function(){return view('admin.login');})->name('admin-login');
	Route::get('dashboard', function(){return view('admin.dashboard');})->name('admin-dashboard');
	Route::get('users', function(){return view('admin.user.list');})->name('admin-users');
	Route::get('products', function(){return view('admin.product.list');})->name('admin-product');
	Route::get('products/create', function(){return view('admin.product.create');})->name('admin-product-create');
	Route::get('bids', function(){return view('admin.bid.list');})->name('admin-bid');
	Route::get('bids/create', function(){return view('admin.bid.create');})->name('admin-bid-create');
	Route::get('categories', function(){return view('admin.category.list');})->name('admin-category');
	Route::get('categories/create', function(){return view('admin.category.create');})->name('admin-category-create');
	Route::get('orders', function(){return view('admin.order.list');})->name('admin-order');
});

Route::get('bid-current/{id}', 'Product\ProductController@getBid')->name('bid-current');
Route::post('bid-current/{id}', 'Product\ProductController@postBid')->name('post-bid-amount');

