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
            Route::namespace('Common')->name('common.')->prefix('common')->group(function () {
                Route::resource('taxes', 'TaxesController', ['except' => ['create']]);
                Route::resource('countries', 'CountriesController', ['only' => ['index']]);
                Route::get('states/{countryId}', 'StatesController@index', ['except' => ['index']]);
                Route::resource('currencies', 'CurrenciesController', ['only' => ['index']]);
                Route::resource('languages', 'LanguagesController', ['only' => ['index']]);
                Route::resource('categories', 'CategoriesController', ['only' => ['index']]);
                Route::get('booking-source', 'EnumsController@bookingSources')->name('booking_source');
                Route::get('booking-start-times', 'EnumsController@bookingStartTimes')->name('booking_start_times');
                Route::get('booking-status', 'EnumsController@bookingStatus')->name('booking_status');
                Route::get('booking-payment-status', 'EnumsController@bookingPaymentStatus')->name('booking_payment_status');
                Route::get('guest-types', 'EnumsController@guestTypes')->name('guest-types');
            });
            Route::namespace('Settings')->name('settings.')->prefix('settings')->group(function () {
                Route::resource('account', 'AccountController', ['except' => ['create']]);
            });
            Route::namespace('Hotels')->name('hotels.')->prefix('hotels')->group(function () {
                Route::resource('hotels', 'HotelsController', ['except' => ['create']]);
                Route::resource('room-types', 'RoomTypesController', ['except' => ['create']]);
                Route::resource('rate-types', 'RateTypesController', ['except' => ['create']]);
                Route::resource('rooms', 'RoomsController', ['except' => ['index', 'create']]);
                Route::get('{hotel}/room-types', 'RoomTypesController@list')->name('room_types_list');
                Route::get('{hotel}/rooms', 'RoomsController@index')->name('rooms_list');
                Route::resource('{hotel}/bookings', 'BookingsController');
                Route::get('room-types/{roomType}/rate-types', 'RateTypesController@rateTypeList')->name('rate_type_list');
                Route::get('room-rate-types/{hotel}', 'HotelsController@loadRoomTypeRateType')->name('load-room-type-rate-type');
                Route::get('change-room/{bookingRoom}', 'BookingsController@changeRoom')->name('change_room');
                Route::get('change-room-and-rate/{bookingRoom}', 'BookingsController@changeRoomAndRate')->name('change_room_and_rate');
            });
        });
    });
});