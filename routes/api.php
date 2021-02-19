<?php

use Illuminate\Http\Request;

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

Route::namespace('Api')->name('api.')->group(function () {
    Route::namespace('V1')->name('v1.')->prefix('v1')->group(function () {
        Route::namespace('Auth')->name('auth.')->prefix('auth')->group(function () {
            Route::post('identify', 'SessionsController@identify')->name('identify');
            Route::post('signin', 'SessionsController@signin')->name('signin');
            Route::post('verify', 'SessionsController@verify')->name('verify');
        });

        Route::middleware('auth:api')->group(function () {
            Route::namespace('Settings')->name('settings.')->prefix('settings')->group(function () {
                Route::resource('account', 'AccountController', ['except' => ['create']]);
            });
            Route::namespace('Hotels')->name('hotels.')->prefix('hotels')->group(function () {
                Route::resource('hotels', 'HotelsController', ['except' => ['create']]);
            });
        });
    });
});