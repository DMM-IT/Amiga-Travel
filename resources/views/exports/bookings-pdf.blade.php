<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
            font-size: 10px;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
            font-size: 18px;
        }
        h2 {
            font-size: 13px;
            margin-top: 30px;
            color: #34495e;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 6px 5px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2980b9;
            font-size: 9px;
        }
        td {
            padding: 5px;
            border: 1px solid #ddd;
            font-size: 9px;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .status {
            display: inline-block;
            padding: 2px 5px;
            border-radius: 3px;
            font-weight: bold;
            font-size: 8px;
        }
        .status-confirmed        { background-color: #27ae60; color: white; }
        .status-pending          { background-color: #f39c12; color: white; }
        .status-cancelled        { background-color: #e74c3c; color: white; }
        .status-operator-cancelled { background-color: #c0392b; color: white; }
        .totals-row td {
            background-color: #ecf0f1;
            font-weight: bold;
            font-size: 9px;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 9px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Booking Report</h1>

    @php
        $sections = [
            ['title' => 'Confirmed Bookings',  'items' => $confirmedBookings],
            ['title' => 'Rebooked Bookings',   'items' => $rebookedBookings],
            ['title' => 'Cancelled Bookings',  'items' => $cancelledBookings],
        ];
    @endphp

    @foreach($sections as $section)
        <h2>{{ $section['title'] }} ({{ $section['items']->count() }})</h2>

        @if($section['items']->count() > 0)
            @php
                $sectionTotal          = $section['items']->sum('total_price');
                $sectionVoucherTotal   = $section['items']->sum('voucher_discount_amount');
            @endphp
            <table>
                <thead>
                    <tr>
                        <th>Transaction #</th>
                        <th>Client Name</th>
                        <th>Contact #</th>
                        <th>Origin</th>
                        <th>Destination</th>
                        <th>Departure Date</th>
                        <th>Return Date</th>
                        <th>Mode</th>
                        <th>Operator</th>
                        <th>Booking Status</th>
                        <th>Amount (₱)</th>
                        <th>Ref # (Payment)</th>
                        <th>Voucher Code</th>
                        <th>Voucher Discount (₱)</th>
                        <th>Gracia Points Used</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($section['items'] as $booking)
                        @php
                            $ferryRoute = $booking->schedule?->ferryRoute;
                        @endphp
                        <tr>
                            <td>{{ $booking->transaction_number }}</td>
                            <td>{{ $booking->client_name }}</td>
                            <td>{{ $booking->client_phone }}</td>
                            <td>{{ $booking->origin }}</td>
                            <td>{{ $booking->destination }}</td>
                            <td>{{ $booking->departure_date?->format('M d, Y') }}</td>
                            <td>{{ $booking->return_date?->format('M d, Y') ?? '-' }}</td>
                            <td>{{ $ferryRoute?->mode ?? $booking->schedule_service ?? '-' }}</td>
                            <td>{{ $ferryRoute?->operator ?? '-' }}</td>
                            <td>
                                <span class="status status-{{ strtolower(str_replace('_', '-', $booking->status)) }}">
                                    {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                                </span>
                            </td>
                            <td>{{ number_format($booking->total_price, 2) }}</td>
                            <td>{{ $booking->transaction?->payment_reference ?? '-' }}</td>
                            <td>{{ filled($booking->voucher_code) ? $booking->voucher_code : '-' }}</td>
                            <td>{{ $booking->voucher_discount_amount > 0 ? number_format($booking->voucher_discount_amount, 2) : '-' }}</td>
                            <td>{{ $booking->points_used > 0 ? number_format($booking->points_used) . ' pts' : '-' }}</td>
                        </tr>
                    @endforeach

                    {{-- Totals row --}}
                    <tr class="totals-row">
                        <td colspan="10" style="text-align:right;">TOTAL AMOUNT</td>
                        <td>{{ number_format($sectionTotal, 2) }}</td>
                        <td colspan="2" style="text-align:right;">TOTAL VOUCHER DISCOUNT</td>
                        <td>{{ number_format($sectionVoucherTotal, 2) }}</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #7f8c8d;">No {{ strtolower($section['title']) }} found.</p>
        @endif
    @endforeach

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s A') }}</p>
        <p>Amiga Gracia Travel &amp; Tours</p>
    </div>
</body>
</html>
