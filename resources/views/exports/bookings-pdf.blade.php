<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            color: #333;
        }
        h1 {
            text-align: center;
            color: #2c3e50;
            margin-bottom: 30px;
            border-bottom: 2px solid #3498db;
            padding-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th {
            background-color: #3498db;
            color: white;
            padding: 12px;
            text-align: left;
            font-weight: bold;
            border: 1px solid #2980b9;
        }
        td {
            padding: 10px 12px;
            border: 1px solid #ddd;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        tr:hover {
            background-color: #ecf0f1;
        }
        .status {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-weight: bold;
            font-size: 12px;
        }
        .status-confirmed {
            background-color: #27ae60;
            color: white;
        }
        .status-pending {
            background-color: #f39c12;
            color: white;
        }
        .status-cancelled {
            background-color: #e74c3c;
            color: white;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #7f8c8d;
            border-top: 1px solid #bdc3c7;
            padding-top: 10px;
        }
    </style>
</head>
<body>
    <h1>Booking Report</h1>
    
    @if($bookings->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Transaction #</th>
                    <th>Client Name</th>
                    <th>Origin</th>
                    <th>Destination</th>
                    <th>Departure Date</th>
                    <th>Status</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach($bookings as $booking)
                    <tr>
                        <td>{{ $booking->id }}</td>
                        <td>{{ $booking->transaction_number }}</td>
                        <td>{{ $booking->client_name }}</td>
                        <td>{{ $booking->origin }}</td>
                        <td>{{ $booking->destination }}</td>
                        <td>{{ $booking->departure_date?->format('M d, Y') }}</td>
                        <td>
                            <span class="status status-{{ strtolower($booking->status) }}">
                                {{ ucfirst($booking->status) }}
                            </span>
                        </td>
                        <td>₱{{ number_format($booking->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <p style="text-align: center; color: #7f8c8d;">No bookings found.</p>
    @endif

    <div class="footer">
        <p>Generated on {{ now()->format('F d, Y \a\t H:i:s A') }}</p>
        <p>Amiga Gracia Travel & Tours</p>
    </div>
</body>
</html>
