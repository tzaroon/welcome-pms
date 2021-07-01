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

        Route::get('booking/payment/{payment}/show-receipt/detailed/{detailed}', 'PdfsController@showReceipt'); //detailed 0 or 1
        Route::get('booking/{booking}/show-voucher', 'PdfsController@showVoucher');
        Route::namespace('Auth')->name('auth.')->prefix('auth')->group(function () {
            Route::post('identify', 'SessionsController@identify')->name('identify');
            Route::post('signin', 'SessionsController@signin')->name('signin');
            Route::post('verify', 'SessionsController@verify')->name('verify');
            Route::get('user-permissions', 'SessionsController@userPermissions')->name('user-permissions');
        });

        Route::namespace('WuBook')->name('wubook.')->prefix('wubook')->group(function () {
            Route::post('push-notification', 'PushNotificationController@index')->name('push-notification');
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
                Route::get('booking-segments', 'EnumsController@segments')->name('booking-segments');
                Route::get('booking-cancel-reasons', 'EnumsController@cancelReasons');
                Route::get('shifts', 'EnumsController@shifts');
                Route::post('upload-id-image', 'ImageController@uploadIdImage');
            });
            Route::namespace('Settings')->name('settings.')->prefix('settings')->group(function () {
                Route::resource('account', 'AccountController', ['except' => ['create']]);
            });
            Route::namespace('Users')->name('users.')->prefix('users')->group(function () {
                Route::resource('bookers', 'BookersController', ['except' => ['create']]);
                Route::get('bookers/autocomplete/{keyword}', 'BookersController@autocomplete')->name('bookers_list');
                Route::resource('user', 'UsersController')->except([
                    'index'
                ]);
                Route::get('user/{role?}/{roleId?}', 'UsersController@index');
                Route::resource('role', 'RolesController');
                Route::resource('shift', 'ShiftsController');
                Route::post('shifts-calendar', 'ShiftsController@showShifts');
                Route::post('role-shifts', 'ShiftsController@addRoleShifts');
                Route::get('permissions', 'ShiftsController@loadPermissions');
                Route::get('role/{role}/shifts', 'RolesController@loadShiftsByRole');
            });

            Route::namespace('Communication')->name('communication.')->prefix('communication')->group(function () {
                Route::get('whats-app', 'WhatsAppController@sendMessage')->name('whats_app');
            });


            Route::namespace('Hotels')->name('hotels.')->prefix('hotels')->group(function () {
                Route::resource('hotels', 'HotelsController', ['except' => ['create']]);
                Route::post('load-rate-types-rate-calculated', 'HotelsController@loadRateTypesWithRateCalculated')->name('load-rate-types-rate-calculated');
                Route::resource('room-types', 'RoomTypesController', ['except' => ['create']]);
                Route::get('bookings/{booking}/status/{status}', 'BookingsController@changeStatus');
                Route::resource('rate-types', 'RateTypesController', ['except' => ['create']]);
                Route::resource('rooms', 'RoomsController', ['except' => ['index', 'create']]);
                Route::post('room-list-by-ids', 'RoomsController@listRoomsByIds')->name('room_list_by_ids');
                Route::get('{hotel}/room-types', 'RoomTypesController@list')->name('room_types_list');
                Route::get('{hotel}/rooms', 'RoomsController@index')->name('rooms_list');
                Route::resource('bookings', 'BookingsController');
                Route::resource('{hotel}/bookings', 'BookingsController');
                Route::post('booking-calendar', 'BookingsController@indexsss');
                Route::post('sand-box-bookings', 'BookingsController@getSandBoxBookings');
                Route::post('sand-box-bookings/assign-room', 'BookingsController@sandBoxBookingAssignRoom');
                Route::post('temporary-closure', 'RoomsController@temporaryClosure');
                Route::resource('{booking}/booking-notes', 'BookingNotesController');
                Route::post('generate-lock', 'BookingsController@generateLock');
                Route::get('booking/payment/{payment}/show-receipt/detailed/{detailed}', 'PaymentsController@showReceipt'); //detailed 0 or 1
                Route::get('booking/{booking}/show-voucher', 'BookingsController@showVoucher');
                Route::get('booking/{booking}/room/{room}/old-price', 'BookingsController@getOldPrice');
                Route::post('booking/save-relocation', 'BookingsController@saveRelocation');
                Route::post('booking/{booking}/update-booking', 'BookingsController@updateBooking');
                Route::get('booking/{booking}/edit-booking', 'BookingsController@editBooking');
                Route::resource('booking/{booking}/booking-payments', 'PaymentsController');
                Route::resource('booking-restrictions', 'RestrictionsController');
                Route::get('bookings/{booking}/products', 'BookingsController@loadProductPrices');
                Route::get('bookings/{booking}/load-rooms', 'BookingsController@loadRooms');
                Route::post('{booking}/change-cleaning-status', 'BookingsController@changeCleaningStatus');
                Route::post('booking-cancel', 'BookingsController@cancel');
                Route::post('rooms/check-avalibility', 'RelocateReservationController@checkAvalibility');
                Route::post('rooms/load-rate-types', 'RelocateReservationController@loadRateTypes');
                Route::post('booking/{booking}/relocateBooking', 'RelocateReservationController@relocateBooking');

                Route::post('daily-rates/bulk-update', 'DailyRatesController@bulkPriceUpdate');
                Route::post('{hotel}/booking-calender', 'DailyRatesController@bookingCalender');
                Route::resource('daily-rates', 'DailyRatesController');
                Route::post('daily-rates/{id}', 'DailyRatesController@index');

                Route::resource('daily-rates', 'DailyRatesController',  ['except' => ['create', 'index']]);
                Route::post('daily-rates', 'DailyRatesController@index');

                Route::get('room-types/{roomType}/rate-types', 'RateTypesController@rateTypeList')->name('rate_type_list');
                Route::get('room-rate-types/{hotel}', 'HotelsController@loadRoomTypeRateType')->name('load-room-type-rate-type');
                Route::post('change-room/{bookingRoom}', 'BookingsController@changeRoom')->name('change_room');
                Route::post('change-room-and-rate/{bookingRoom}', 'BookingsController@changeRoomAndRate')->name('change_room_and_rate');
                Route::get('change-room-and-rate/{bookingRoom}', 'BookingsController@changeRoomAndRate')->name('change_room_and_rate');
                Route::get('{hotel}/extras', 'ExtrasController@index', ['except' => ['create']]);
                Route::resource('extras', 'ExtrasController', ['except' => ['create', 'index']]);
                Route::get('{hotel}/extras/autocomplete/{keyword}', 'ExtrasController@autocomplete', ['except' => ['create', 'index']]);
                Route::namespace('Booking')->name('booking.')->prefix('booking')->group(function () {
                    Route::get('invoices/{invoice}/edit', 'InvoicesController@edit')->name('booking_invoices');
                    Route::get('invoices/{booking}/{proforma}', 'InvoicesController@index')->name('booking_invoices');
                    Route::put('invoices/{invoice}', 'InvoicesController@update')->name('booking_invoice_update');
                    Route::resource('{booking}/invoices', 'InvoicesController', ['except' => ['create', 'index']]);
                });
            });
        });
    });
});
