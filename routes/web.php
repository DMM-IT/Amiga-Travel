<?php

use App\Http\Controllers\AuthController;
use App\Models\Transaction;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Route;

$renderWebsitePage = function (string $page, string $view) {
    $settings = WebsiteSetting::firstWhere('page', $page);

    return view($view, [
        'pageSettings' => $settings,
        'pageContent' => $settings->content ?? [],
        'heroImages' => collect($settings->hero_images ?? []),
        'bookingCards' => $settings->booking_cards ?? [],
    ]);
};

Route::get('/', function () use ($renderWebsitePage) {
    return $renderWebsitePage('home', 'home');
})->name('home');
Route::view('/book/new', 'book')->name('book.new');
Route::view('/book/status', 'book-status')->name('book.status');

Route::get('/about', function () use ($renderWebsitePage) {
    return $renderWebsitePage('about', 'about');
})->name('about');

Route::get('/gallery', function () use ($renderWebsitePage) {
    return $renderWebsitePage('gallery', 'gallery');
})->name('gallery');

Route::get('/services', function () use ($renderWebsitePage) {
    return $renderWebsitePage('services', 'services');
})->name('services');

Route::get('/tour-package', function () use ($renderWebsitePage) {
    return $renderWebsitePage('tour-package', 'tour-package');
})->name('tour-package');

Route::get('/contact-us', function () use ($renderWebsitePage) {
    return $renderWebsitePage('contact_us', 'contact');
})->name('contact');

Route::get('/download', function () use ($renderWebsitePage) {
    return $renderWebsitePage('download', 'download');
})->name('download');

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
