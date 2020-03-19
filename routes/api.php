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

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });
Route::group(['namespace' => 'api', 'as' => 'api.'], function () {
	Route::group(['namespace' => 'v1', 'prefix' => 'v1', 'as' => 'v1.'], function () {
        Route::get('test', 'TestController@test')->name('test');

        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {
            Route::post('login', 'AuthController@login')->name('login');
            Route::group(['middleware' => 'auth:api'], function () {
                Route::post('refresh_token', 'AuthController@refresh_token')->name('refresh_token');
            });
            Route::post('register', 'AuthController@register')->name('register');

        });

        Route::group(['middleware' => 'auth:api', 'namespace' => 'user', 'prefix' => 'user', 'as' => 'user.'], function () {
            Route::post('books', 'BooksController@create')->name('books.create');
            Route::get('books', 'BooksController@index')->name('books.index');
            Route::put('books', 'BooksController@update')->name('books.update');
            Route::delete('books', 'BooksController@delete')->name('books.delete');

            Route::post('books/recommend/read', 'BooksController@recommend_read')->name('books.recommend.read');
        });

        Route::group(['middleware' => 'auth:api', 'prefix' => 'books', 'as' => 'books.'], function () {
            Route::get('recommend', 'BooksController@recommend')->name('recommend');
            Route::get('{book_id}/detail', 'BooksController@detail')->name('detail'); // 사용자 프로필 데이터 전달.
        });
	});
});
