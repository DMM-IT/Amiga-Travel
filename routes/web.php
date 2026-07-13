<?php

use App\Http\Controllers\AuthController;
use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    $promotions = \App\Models\Promotion::where('is_active', true)->get();
    return view('home', compact('promotions'));
})->name('home');
Route::view('/book/new', 'book')->name('book.new');
Route::view('/book/status', 'book-status')->name('book.status');

Route::view('/about', 'about')->name('about');
Route::view('/gallery', 'gallery')->name('gallery');
Route::view('/services', 'services')->name('services');
Route::view('/tour-package', 'tour-package')->name('tour-package');
Route::view('/contact-us', 'contact')->name('contact');
Route::view('/download', 'download')->name('download');

Route::get('/payment/{transaction}', function (Transaction $transaction) {
    $transaction->load('booking');

    return view('payment', [
        'transaction' => $transaction,
        'qrCodePath' => App\Models\PaymentSetting::current()->qr_code_path,
    ]);
})->name('payment.show');

Route::get('/ticket/download/{booking}', function (App\Models\Booking $booking) {
    if (! request()->hasValidSignature()) {
        abort(403);
    }

    $path = storage_path('app/receipts/receipt-' . $booking->transaction_number . '.pdf');

    if (! file_exists($path)) {
        abort(404);
    }

    return response()->file($path, [
        'Content-Type' => 'application/pdf',
        'Content-Disposition' => 'attachment; filename="receipt-'. $booking->transaction_number .'.pdf"',
    ]);
})->name('ticket.download');

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth')->name('dashboard');

Route::get('/flutter-app', function () {
    return view('flutter');
})->name('flutter.app');

