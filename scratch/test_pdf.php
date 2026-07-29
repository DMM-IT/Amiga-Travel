<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Booking;
use Dompdf\Dompdf;
use Dompdf\Options;

try {
    $booking = Booking::where('transaction_number', 'AGT-20260729-8139')->firstOrFail();
    $booking->load('passengers.discount', 'scheduleAccommodation', 'transportClasses', 'transaction', 'schedule');

    echo "Loaded booking successfully.\n";

    $pdfOptions = new Options();
    $pdfOptions->set('isRemoteEnabled', false);
    $pdfOptions->set('isHtml5ParserEnabled', true);
    $pdfOptions->set('defaultFont', 'DejaVu Sans');
    
    $dompdf = new Dompdf($pdfOptions);
    $html = view('pdf.receipt', ['booking' => $booking])->render();
    
    echo "Rendered HTML template successfully. Length: " . strlen($html) . "\n";
    
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    
    $output = $dompdf->output();
    echo "Rendered PDF successfully. Length: " . strlen($output) . "\n";
    
    $receiptPath = __DIR__ . '/receipt_test.pdf';
    file_put_contents($receiptPath, $output);
    echo "Saved to $receiptPath\n";
    
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
