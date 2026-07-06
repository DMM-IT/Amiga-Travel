<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Booking Confirmation</title>
    </head>
    <body style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937;">
        <h1>Booking Confirmed</h1>
        <p>Thank you, {{ $booking->client_name }}.</p>
        <p>Your booking has been created successfully.</p>
        <ul>
            <li><strong>Transaction:</strong> {{ $booking->transaction_number }}</li>
            <li><strong>Origin:</strong> {{ $booking->origin }}</li>
            <li><strong>Destination:</strong> {{ $booking->destination }}</li>
            <li><strong>Departure:</strong> {{ $booking->departure_date }}</li>
            <li><strong>Return:</strong> {{ $booking->return_date ?? 'One-way' }}</li>
            <li><strong>Adults:</strong> {{ $booking->passengers->where('type', 'adult')->count() }}</li>
            <li><strong>Children:</strong> {{ $booking->passengers->where('type', 'child')->count() }}</li>
            <li><strong>Infants:</strong> {{ $booking->passengers->where('type', 'infant')->count() }}</li>
        </ul>
        <p>
            Complete payment at the following page:
            <a href="{{ route('payment.show', ['transaction' => $booking->transaction->id]) }}">{{ route('payment.show', ['transaction' => $booking->transaction->id]) }}</a>
        </p>
        <p>
            Download your ticket here:
            <a href="{{ $ticketUrl }}">Download receipt/ticket</a>
        </p>
    </body>
</html>
