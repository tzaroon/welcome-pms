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

Route::get('/web-check-in/{bookingCode}', [QrCodeController::class, 'webCheckIn']);
Route::get('/web-check-in/{bookingCode}/guests', [BookingGuestController::class, 'guests']);
Route::get('/web-check-in/{bookingCode}/get-guests/{userId?}', [BookingGuestController::class, 'getGuestDetails']);
Route::post('/web-check-in/{bookingCode}/add-guest-details', [BookingGuestController::class, 'addGuestDetails'])->name('guest.create');
