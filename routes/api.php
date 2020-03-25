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

        Route::get('test', 'TestController@test')->name('test'); // 테스트 라우터.

        Route::group(['prefix' => 'auth', 'as' => 'auth.'], function () {

            Route::post('login', 'AuthController@login')->name('login'); // 로그인 라우터.
            // Route::group(['middleware' => 'auth:api'], function () {
                Route::post('refresh_token', 'AuthController@refresh_token')->name('refresh_token'); // 토큰 리프레쉬 라우터. ( access_token 처리 빼기로.)
            // });
            Route::post('register', 'AuthController@register')->name('register'); // 회원 가입.

        });

        Route::group(['middleware' => 'auth:api', 'namespace' => 'user', 'prefix' => 'user', 'as' => 'user.'], function () {
            Route::post('books', 'BooksController@create')->name('books.create'); // 사용자 책 등록.
            Route::get('books', 'BooksController@index')->name('books.index'); // 등록책 리스트.
            Route::get('books/page/{page}', 'BooksController@index_page_type')->name('books.index_page_type'); // 등록책 리스트 페이징 타입.
            Route::put('books', 'BooksController@update')->name('books.update'); // 업데이트?
            Route::delete('books', 'BooksController@delete')->name('books.delete'); // 삭제?

            Route::post('books/activity', 'BooksController@create_activity')->name('activity.create'); // 독서 활동 등록.
            Route::delete('books/activity', 'BooksController@delete_activity')->name('activity.detele'); // 독서 활동 삭제.
            Route::post('books/recommend/read', 'BooksController@recommend_read')->name('books.recommend.read'); // 읽은 책 등록.
            Route::delete('books/recommend/read', 'BooksController@recommend_read_delete')->name('books.recommend.read.delete'); // 읽은 책 등록 삭제.

            Route::get('setting', 'UserController@setting')->name('setting'); // 사용자 설정 페이지.
            Route::post('setting/activity_state', 'UserController@update_activity')->name('setting.activity_state'); // 사용자 설정 활동 여부 수정.
        });

        Route::group(['middleware' => 'auth:api', 'prefix' => 'system', 'as' => 'system.'], function () {
            Route::get('basedata', 'SystemController@basedata')->name('basedata'); // 기본 데이터.

            Route::get('commoncode', 'SystemController@commoncode')->name('commoncode.index'); // 시스템 공통 코드.
            Route::get('commoncode/group/{group_id}/list', 'SystemController@commoncode_group_list')->name('commoncode.group.list'); // 시스템 공통 코드 리스트 조회?.
        });

        Route::group(['middleware' => 'auth:api', 'prefix' => 'books', 'as' => 'books.'], function () {
            Route::get('recommend', 'BooksController@recommend')->name('recommend'); // 추천 도서 리스트.
            Route::get('{gubun}/recommend/page/{page}', 'BooksController@recommend_category')->name('recommend_category'); // 추천 도서 카테고리별 페이징.
            Route::get('recommend/page/{page}', 'BooksController@recommend_pagetype')->name('recommend_page_type'); // 추천 도서 리스트 페이징 타입.
            Route::get('{book_id}/detail', 'BooksController@detail')->name('detail'); // 책 상세 정보.
            Route::get('search', 'BooksController@search')->name('search'); // 책 검색.
        });
	});
});
