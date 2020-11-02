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

Route::group(['middleware' => ['auth:api']], function () {
    Route::group(['middleware' => ['role:admin'], 'as' => 'api.'], function () {
        Route::apiResource('users', 'Api\UserController');
        Route::apiResource('tables', 'Api\TableController');
        Route::apiResource('categories', 'Api\CategoryController');

        Route::put('foods/stock', 'Api\FoodController@stock')->name('foods.stock');
        Route::apiResource('foods', 'Api\FoodController')->only(['update']);
    });
});
