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
    return view('index');
});

Route::group([
    'prefix' => 'test'
], function() {
    Route::get('/', 'TestController@test');
});

Route::group([
    'prefix' => 'admin',
], function() {
    Route::post('register', 'AdminController@register');
    Route::post('login', 'AdminController@login');
    Route::get('logout', 'AdminController@logout');
});

Route::group([
    'prefix' => 'user',
], function() {
    Route::get('list', 'UserController@list');
    Route::get('label', 'UserController@label');
    Route::put('opt', 'UserController@opt');
    Route::put('reset', 'UserController@opt');
});

Route::group([
    'prefix' => 'farmer',
], function() {
    Route::get('list', 'FarmerController@list');
    Route::get('label', 'FarmerController@label');
});

Route::group([
    'prefix' => 'dealer',
], function() {
    Route::get('list', 'DealerController@list');
    Route::get('label', 'DealerController@label');
});