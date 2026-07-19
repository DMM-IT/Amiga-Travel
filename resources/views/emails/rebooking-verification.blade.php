<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Rebooking Verified</title>
    </head>
    <body style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937;">
        <h1>Rebooking Verified</h1>
        <p>Hi {{ $booking->client_name }},</p>
        <p>Your rebooking request has been verified successfully.</p>
        <p>
            Transaction: <strong>{{ $booking->transaction_number }}</strong><br>
            New departure: <strong>{{ $booking->departure_date }}</strong><br>
            New return: <strong>{{ $booking->return_date ?? 'One-way' }}</strong>
        </p>
        @if(! empty($ticketUrl))
            <p>
                You can download your ticket here:
                <a href="{{ $ticketUrl }}">Download ticket</a>
            </p>
        @endif
        <p>Thank you for choosing Amiga Gracia Travel.</p>
    </body>
</html>
