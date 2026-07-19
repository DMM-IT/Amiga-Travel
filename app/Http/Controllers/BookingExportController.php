<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\StreamedResponse;

class BookingExportController extends Controller
{
    public function exportPdf()
    {
        return $this->generatePdfResponse('bookings.pdf', false);
    }

    public function exportCsv()
    {
        $bookings = Booking::all();

        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="bookings.csv"',
        ];

        $callback = function () use ($bookings) {
            $file = fopen('php://output', 'w');

            // CSV Header
            fputcsv($file, [
                'ID',
                'Transaction #',
                'Client Name',
                'Client Email',
                'Origin',
                'Destination',
                'Departure Date',
                'Return Date',
                'Status',
                'Total Price',
                'Created At',
            ]);

            // CSV Rows
            foreach ($bookings as $booking) {
                fputcsv($file, [
                    $booking->id,
                    $booking->transaction_number,
                    $booking->client_name,
                    $booking->client_email,
                    $booking->origin,
                    $booking->destination,
                    $booking->departure_date?->format('Y-m-d'),
                    $booking->return_date?->format('Y-m-d'),
                    $booking->status,
                    $booking->total_price,
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($file);
        };

        return new StreamedResponse($callback, 200, $headers);
    }

    public function exportPrint()
    {
        return $this->generatePdfResponse('bookings.pdf', true);
    }

    protected function generatePdfResponse(string $filename, bool $inline = false): Response
    {
        $bookings = Booking::all();
        $html = view('exports.bookings-pdf', ['bookings' => $bookings])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'Arial');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        $output = $dompdf->output();

        $headers = [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="' . $filename . '"',
        ];

        return new Response($output, 200, $headers);
    }
}
