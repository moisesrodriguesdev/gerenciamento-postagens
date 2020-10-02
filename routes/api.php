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

Route::namespace('Api')->group(function () {
    Route::namespace('JWTAuth')->group(function () {
        Route::group(['prefix' => 'auth'], function () {
            Route::post('register', 'JWTAuthController@register');
            Route::post('login', 'JWTAuthController@login');
            Route::post('auth', 'JWTAuthController@auth');
        });
    });

    Route::namespace('Post')->group(function () {
        Route::middleware('api.jwt')->group(function () {
            Route::get('post', 'PostController@index');
            Route::post('post', 'PostController@store');

            Route::middleware('post')->group(function () {
                Route::put('post/{postId}', 'PostController@update');
                Route::delete('post/{postId}', 'PostController@delete');
            });
        });
    });
});
