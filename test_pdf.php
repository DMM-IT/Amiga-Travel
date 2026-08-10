<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$booking = \App\Models\Booking::with(['passengers.discount', 'schedule.route', 'returnSchedule', 'transaction', 'accommodations', 'transportClasses'])->first();
if (!$booking) { echo "No booking found\n"; exit; }

$receiptDir = storage_path('app/receipts');
$path = $receiptDir . '/receipt-' . $booking->transaction_number . '.pdf';

try {
    if (! is_dir($receiptDir)) {
        mkdir($receiptDir, 0755, true);
    }
    echo "Generating PDF...\n";
    \Spatie\LaravelPdf\Facades\Pdf::view('pdf.receipt', ['booking' => $booking])
        ->format('a4')
        ->save($path);
    echo "PDF generated at: $path\n";
    echo "File exists: " . (file_exists($path) ? 'Yes' : 'No') . "\n";
} catch (\Throwable $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
