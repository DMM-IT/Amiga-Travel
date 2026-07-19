<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\AccommodationController;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);

Route::get('/origins', [ScheduleController::class, 'origins']);
Route::get('/destinations', [ScheduleController::class, 'destinations']);
Route::post('/schedules', [ScheduleController::class, 'search']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings/{id}/proof', [BookingController::class, 'uploadProof']);
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
Route::get('/payment-settings', [BookingController::class, 'paymentSettings']);

Route::get('/promotions', [PromotionController::class, 'index']);
Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('/accommodations', [AccommodationController::class, 'index']);
Route::get('/tours', [\App\Http\Controllers\Api\TourController::class, 'index']);
Route::get('/vehicle-rates', [BookingController::class, 'vehicleRates']);
