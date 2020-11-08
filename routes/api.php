<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['middleware' => ['auth:api'], 'as' => 'api.'], function () {
    Route::group(['middleware' => ['role:admin']], function () {
        Route::apiResource('users', 'Api\UserController');
        Route::apiResource('tables', 'Api\TableController');
        Route::apiResource('categories', 'Api\CategoryController');

        Route::put('foods/stock', 'Api\FoodController@stock')->name('foods.stock');
    });

    Route::apiResource('foods', 'Api\FoodController')->only(['update', 'show']);
    Route::get('/orders/find', 'Api\OrderController@find')->name('orders.find');
    Route::apiResource('/orders', 'Api\OrderController');
});

Route::post('/login', 'Api\AuthController@login')->name('auth.login');