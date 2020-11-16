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

Route::get('/', function () {
    return view('welcome');
});

Auth::routes(['register' => false]);
Route::post('/login', 'Api\AuthController@login')->name('auth.login');
Route::group(['middleware' => ['auth:web', 'auth:api']], function () {
    Route::post('/logout', 'Api\AuthController@logout')->name('auth.logout');
});

Route::get('/home', 'HomeController@index')->name('home');

Route::group(['middleware' => ['auth']], function () {
    Route::get('/foods/stock', 'FoodController@stock')->name('foods.stock');
    
    Route::group(['middleware' => ['role:admin'], 'as' => 'admin.'], function() {
        Route::get('/settings', 'SettingController@index')->name('settings');
        Route::put('/settings', 'SettingController@update')->name('settings.update');
    
        Route::get('/users', 'UserController@index')->name('users');
        Route::resource('/tables', 'TableController')->only(['index', 'show', 'destroy', 'create', 'store']);
        Route::get('/categories', 'CategoryController@index')->name('categories');

        Route::resource('/foods', 'FoodController');
    });

    Route::get('/orders/print/{order}', 'OrderController@print')->name('orders.print');
    Route::get('/orders/queue', 'OrderController@queue')->name('orders.queue');
    Route::get('/orders/ready', 'OrderController@ready')->name('orders.ready');
    Route::get('/orders/finish', 'OrderController@finish')->name('orders.finish');
    Route::resource('/orders', 'OrderController')->only(['index', 'show', 'destroy']);
    
    Route::get('/profile', 'ProfileController@index')->name('profile');
    Route::put('/profile', 'ProfileController@update')->name('profile.update');
});