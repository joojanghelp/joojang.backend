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


Route::group(['namespace' => 'web', 'prefix' => 'web', 'as' => 'web.'], function () {
	Route::group(['namespace' => 'v1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
        Route::get('test', 'AuthController@test')->name('test'); // 테스트용.

        Route::group(['prefix' => 'auth','as' => 'auth.'], function () {
            Route::get('email_auth', 'AuthController@email_auth')->name('email_auth');// 회원 이메일 인증.

        });

	});
});
