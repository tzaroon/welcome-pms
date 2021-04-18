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
                Route::get('extra-settings', 'ExtraSettingsController@index')->name('extra-settings');
                Route::get('document-types', 'EnumsController@documentTypes')->name('document-types');
                Route::get('genders', 'EnumsController@genders')->name('genders');
                Route::get('extras-pricing', 'EnumsController@extrasPricing')->name('extras-pricing');
                Route::get('payment-methods', 'EnumsController@paymentMethods')->name('payment-methods');
            });
            Route::namespace('Settings')->name('settings.')->prefix('settings')->group(function () {
                Route::resource('account', 'AccountController', ['except' => ['create']]);
            });
            Route::namespace('Users')->name('users.')->prefix('users')->group(function () {
                Route::resource('bookers', 'BookersController', ['except' => ['create']]);
                Route::get('bookers/autocomplete/{keyword}', 'BookersController@autocomplete')->name('bookers_list');
            });
            Route::namespace('Communication')->name('communication.')->prefix('communication')->group(function (){
                Route::get('whats-app', 'WhatsAppController@sendMessage')->name('whats_app');
            });
            Route::namespace('WuBook')->name('wubook.')->prefix('wubook')->group(function (){
                Route::post('push-notification', 'PushNotificationController@index')->name('push-notification');
            });
            Route::namespace('Hotels')->name('hotels.')->prefix('hotels')->group(function () {
                Route::resource('hotels', 'HotelsController', ['except' => ['create']]);
                Route::resource('room-types', 'RoomTypesController', ['except' => ['create']]);
                Route::resource('rate-types', 'RateTypesController', ['except' => ['create']]);
                Route::resource('rooms', 'RoomsController', ['except' => ['index', 'create']]);
                Route::post('room-list-by-ids', 'RoomsController@listRoomsByIds')->name('room_list_by_ids');
                Route::get('{hotel}/room-types', 'RoomTypesController@list')->name('room_types_list');
                Route::get('{hotel}/rooms', 'RoomsController@index')->name('rooms_list');
                Route::resource('{hotel}/bookings', 'BookingsController');
                Route::resource('bookings', 'BookingsController');
                Route::post('daily-rates/{id}', 'DailyRatesController@index');
                Route::get('room-types/{roomType}/rate-types', 'RateTypesController@rateTypeList')->name('rate_type_list');
                Route::get('room-rate-types/{hotel}', 'HotelsController@loadRoomTypeRateType')->name('load-room-type-rate-type');
                Route::post('change-room/{bookingRoom}', 'BookingsController@changeRoom')->name('change_room');
                Route::post('change-room-and-rate/{bookingRoom}', 'BookingsController@changeRoomAndRate')->name('change_room_and_rate');
                Route::get('change-room-and-rate/{bookingRoom}', 'BookingsController@changeRoomAndRate')->name('change_room_and_rate');
                Route::get('{hotel}/extras', 'ExtrasController@index', ['except' => ['create']]);
                Route::resource('extras', 'ExtrasController', ['except' => ['create', 'index']]);
                Route::get('{hotel}/extras/autocomplete/{keyword}', 'ExtrasController@autocomplete', ['except' => ['create', 'index']]);
            });
        });
    });
});