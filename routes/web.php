<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\QrCodeController;
use App\Http\Controllers\BookingGuestController;

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


Route::get('getState',[BookingGuestController::class, 'getState'])->name('getState');

Route::get('/web-check-in/{bookingCode}', [QrCodeController::class, 'webCheckIn']);
Route::get('/web-check-in/{bookingCode}/terms-and-conditions', [QrCodeController::class, 'termsAndConditions']);

Route::get('/web-check-in/{bookingCode}/guests', [BookingGuestController::class, 'guests']);

Route::get('/web-check-in/{bookingCode}/get-booker/{userId}', [BookingGuestController::class, 'getBookerDetails']);
Route::get('/web-check-in/{bookingCode}/get-guest/{guestId}', [BookingGuestController::class, 'getGuestDetails']);
Route::get('/web-check-in/{bookingCode}/add-guest', [BookingGuestController::class, 'addGuest']);

Route::post('/web-check-in/{bookingCode}/add-guest-details', [BookingGuestController::class, 'addGuestDetails']);
Route::post('/web-check-in/{bookingCode}/add-booker-details', [BookingGuestController::class, 'addBookerDetails']);

Route::get('/web-check-in/{bookingCode}/payment-details', [BookingGuestController::class, 'paymentDetails']);
Route::get('/web-check-in/{bookingCode}/payment', [BookingGuestController::class, 'makePayment']);

