<?php

use App\Models\Transaction;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/book', App\Livewire\BookingForm::class);

Route::get('/payment/{transaction}', function (Transaction $transaction) {
    $transaction->load('booking');

    return view('payment', [
        'transaction' => $transaction,
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
