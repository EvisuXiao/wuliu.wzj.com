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
});

Route::group([
    'prefix' => 'farmer',
], function() {
    Route::get('list', 'FarmerController@list');
    Route::get('label', 'FarmerController@label');
    Route::match(['get', 'put'], 'info', 'FarmerController@info');
    Route::match(['get', 'post', 'put'], 'apply', 'FarmerController@apply');
    Route::match(['get', 'post'], 'withdraw', 'FarmerController@withdraw');
    Route::get('withdrawLabel', 'FarmerController@withdrawLabel');
});

Route::group([
    'prefix' => 'dealer',
], function() {
    Route::get('list', 'DealerController@list');
    Route::get('label', 'DealerController@label');
    Route::get('info', 'DealerController@info');
});

Route::group([
    'prefix' => 'bank',
], function() {
    Route::get('list', 'BankController@list');
    Route::get('label', 'BankController@label');
    Route::get('info', 'BankController@info');
    Route::put('opt', 'BankController@opt');
    Route::get('applyScore', 'BankController@applyScore');
    Route::get('score', 'BankController@score');
    Route::match(['get', 'put'], 'cfg', 'BankController@cfg');
    Route::post('pool', 'BankController@pool');
    Route::get('farmerApply', 'BankController@farmerApply');
    Route::get('farmerApplyLabel', 'BankController@farmerApplyLabel');
    Route::put('summary', 'BankController@summary');
});

Route::group([
    'prefix' => 'company',
], function() {
    Route::get('list', 'CompanyController@list');
    Route::get('farmer', 'CompanyController@farmer');
    Route::match(['get', 'put'], 'info', 'CompanyController@info');
    Route::put('repay', 'CompanyController@repay');
});