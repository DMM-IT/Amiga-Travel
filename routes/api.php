<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\Api\ScheduleController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\PromotionController;
use App\Http\Controllers\Api\DiscountController;
use App\Http\Controllers\Api\AccommodationController;
use App\Models\Inquiry;
use Illuminate\Support\Facades\Route;

Route::post('/login', [AuthController::class, 'apiLogin']);
Route::post('/register', [AuthController::class, 'apiRegister']);
Route::post('/register/request-otp', [AuthController::class, 'requestRegisterOtp']);
Route::post('/register/verify-otp', [AuthController::class, 'verifyRegisterOtp']);
Route::post('/email-verification/request', [AuthController::class, 'requestEmailVerification']);
Route::post('/email-verification/verify', [AuthController::class, 'verifyEmail']);

Route::get('/origins', [ScheduleController::class, 'origins']);
Route::get('/destinations', [ScheduleController::class, 'destinations']);
Route::get('/operators', [ScheduleController::class, 'operators']);
Route::post('/schedules', [ScheduleController::class, 'search']);
Route::get('/all-schedules', [ScheduleController::class, 'allSchedules']);
Route::post('/bookings', [BookingController::class, 'store']);
Route::get('/bookings', [BookingController::class, 'index']);
Route::post('/bookings/{id}/proof', [BookingController::class, 'uploadProof']);
Route::post('/bookings/{id}/cancel', [BookingController::class, 'cancel']);
Route::post('/bookings/{id}/rebook', [BookingController::class, 'rebook']);
Route::get('/payment-settings', [BookingController::class, 'paymentSettings']);
Route::post('/support', function (Illuminate\Http\Request $request) {
	$data = $request->validate([
		'name' => 'required|string|max:255',
		'email' => 'required|email|max:255',
		'subject' => 'nullable|string|max:255',
		'message' => 'required|string',
	]);

	Inquiry::create($data);

	return response()->json(['status' => 'success', 'message' => 'Support request received.']);
});

Route::get('/promotions', [PromotionController::class, 'index']);
Route::get('/discounts', [DiscountController::class, 'index']);
Route::get('/vouchers', [\App\Http\Controllers\Api\VoucherController::class, 'index']);
Route::post('/vouchers/validate', [\App\Http\Controllers\Api\VoucherController::class, 'validateVoucher']);
Route::get('/accommodations', [AccommodationController::class, 'index']);
Route::get('/tours', [\App\Http\Controllers\Api\TourController::class, 'index']);
Route::get('/services', function () {
    $settings = \App\Models\WebsiteSetting::where('page', 'services')->first();
    $cards = [];
    if ($settings && isset($settings->content['travel_service_cards'])) {
        $cards = $settings->content['travel_service_cards'];
    }
    return response()->json([
        'status' => 'success',
        'services' => $cards,
    ]);
});
Route::get('/vehicle-rates', [BookingController::class, 'vehicleRates']);

Route::middleware('auth:api')->group(function () {
    Route::get('/gracia-points', [\App\Http\Controllers\Api\GraciaPointsController::class, 'index']);
});

Route::get('/app-version', function () {
    $pubspecPath = base_path('flutter_app/pubspec.yaml');
    $version = '1.0.0+1';
    if (file_exists($pubspecPath)) {
        $content = file_get_contents($pubspecPath);
        if (preg_match('/^version:\s*(.+)$/m', $content, $matches)) {
            $version = trim($matches[1]);
        }
    }
    return response()->json([
        'version' => $version,
        'force_update' => true,
    ]);
});
