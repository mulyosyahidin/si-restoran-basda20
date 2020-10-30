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
//Route::post('/logout', 'Api\AuthController@logout')->name('logout');

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/admin', 'Admin\AdminController@index');

Route::group(['middleware' => ['role:admin'], 'as' => 'admin.'], function() {
    Route::get('/settings', 'Admin\SettingController@index')->name('settings');
    Route::put('/settings', 'Admin\SettingController@update')->name('settings.update');
});
