<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\BookingExportController;
use App\Models\Transaction;
use App\Models\WebsiteSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TourController;

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

Route::get('/tours', [TourController::class, 'index'])->name('tours.index');
Route::get('/tours/{tour}', [TourController::class, 'show'])->name('tours.show');

Route::post('/booking/draft/cancel', function (Request $request) {
    $request->session()->forget('booking_draft');

    return redirect()->route('home');
})->name('booking.draft.cancel');

Route::redirect('/book', '/book/new')->name('book');
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

Route::post('/contact-us', function (Illuminate\Http\Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'subject' => 'nullable|string|max:255',
        'message' => 'required|string',
    ]);

    App\Models\Inquiry::create($data);

    return response()->json(['message' => 'Inquiry received']);
})->name('contact.submit');

Route::get('/download', function () use ($renderWebsitePage) {
    return $renderWebsitePage('download', 'download');
})->name('download');

Route::get('/schedules', function (\Illuminate\Http\Request $request) {
    $startDate = $request->query('start_date', \Carbon\Carbon::today()->format('Y-m-d'));
    $endDate = $request->query('end_date', \Carbon\Carbon::today()->addDays(6)->format('Y-m-d'));

    $routes = App\Models\FerryRoute::with([
        'schedules' => function ($query) use ($startDate, $endDate) {
            $query->where('is_active', true)
                  ->whereBetween('departure_time', [$startDate . ' 00:00:00', $endDate . ' 23:59:59'])
                  ->orderBy('departure_time');
        },
        'schedules.scheduleAccommodations',
        'schedules.transportClasses',
    ])->where('is_active', true)->orderBy('origin')->orderBy('destination')->get();
    
    // Filter out routes that have no schedules in this date range
    $routes = $routes->filter(fn ($route) => $route->schedules->isNotEmpty());

    return view('schedules', compact('routes', 'startDate', 'endDate'));
})->name('schedules');

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

// Booking Export Routes (Admin only)
Route::middleware(['auth', 'admin'])->group(function () {
    Route::get('/admin/bookings/export/pdf', [BookingExportController::class, 'exportPdf'])->name('bookings.export.pdf');
    Route::get('/admin/bookings/export/csv', [BookingExportController::class, 'exportCsv'])->name('bookings.export.csv');
    Route::get('/admin/bookings/export/print', [BookingExportController::class, 'exportPrint'])->name('bookings.export.print');
});

