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
        $bookings = Booking::with(['transaction', 'schedule.ferryRoute'])->get();

        $headers = [
            'Content-Type'        => 'text/csv',
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
                'Contact Number',
                'Origin',
                'Destination',
                'Departure Date',
                'Return Date',
                'Mode',
                'Operator',
                'Booking Status',
                'Payment Status',
                'Amount',
                'Payment Reference #',
                'Voucher Code',
                'Voucher Discount (₱)',
                'Gracia Points Used',
                'Created At',
            ]);

            $totalAmount          = 0;
            $totalVoucherDiscount = 0;

            // CSV Rows
            foreach ($bookings as $booking) {
                $totalAmount          += (float) $booking->total_price;
                $totalVoucherDiscount += (float) ($booking->voucher_discount_amount ?? 0);
                $ferryRoute = $booking->schedule?->ferryRoute;

                fputcsv($file, [
                    $booking->id,
                    $booking->transaction_number,
                    $booking->client_name,
                    $booking->client_email,
                    $booking->client_phone,
                    $booking->origin,
                    $booking->destination,
                    $booking->departure_date?->format('Y-m-d'),
                    $booking->return_date?->format('Y-m-d') ?? '',
                    $ferryRoute?->mode ?? $booking->schedule_service ?? '',
                    $ferryRoute?->operator ?? '',
                    ucfirst($booking->status),
                    $booking->transaction?->payment_status ?? '',
                    number_format($booking->total_price, 2),
                    $booking->transaction?->payment_reference ?? '',
                    $booking->voucher_code ?? '',
                    $booking->voucher_discount_amount > 0 ? number_format($booking->voucher_discount_amount, 2) : '',
                    $booking->points_used > 0 ? $booking->points_used : '',
                    $booking->created_at->format('Y-m-d H:i:s'),
                ]);
            }

            // Totals row — placed below all records in the Amount column position
            fputcsv($file, []);
            fputcsv($file, [
                '', '', '', '', '', '', '', '', '', '', '', '',
                'TOTAL AMOUNT', number_format($totalAmount, 2),
                '', 'TOTAL VOUCHER DISCOUNT', number_format($totalVoucherDiscount, 2),
                '', '',
            ]);

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
        $bookings = Booking::with(['transaction', 'schedule.ferryRoute'])->get();

        $confirmedBookings = $bookings->filter(function ($booking) {
            return $booking->status === Booking::STATUS_CONFIRMED && ! $booking->is_rebooked;
        });

        $rebookedBookings = $bookings->filter(function ($booking) {
            return $booking->is_rebooked || filled($booking->rebooking_status);
        });

        $cancelledBookings = $bookings->filter(function ($booking) {
            return in_array($booking->status, [Booking::STATUS_CANCELLED, Booking::STATUS_OPERATOR_CANCELLED], true);
        });

        $html = view('exports.bookings-pdf', [
            'confirmedBookings' => $confirmedBookings,
            'rebookedBookings'  => $rebookedBookings,
            'cancelledBookings' => $cancelledBookings,
        ])->render();

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $options->set('defaultFont', 'DejaVu Sans');

        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        $output = $dompdf->output();

        $headers = [
            'Content-Type'        => 'application/pdf',
            'Content-Disposition' => ($inline ? 'inline' : 'attachment') . '; filename="' . $filename . '"',
        ];

        return new Response($output, 200, $headers);
    }
}
