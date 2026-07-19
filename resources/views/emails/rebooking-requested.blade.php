<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Rebooking Request Received</title>
    </head>
    <body style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937;">
        <h1>Rebooking Request Received</h1>
        <p>Hi {{ $booking->client_name }},</p>
        <p>We have received your rebooking request and payment proof. An admin will verify it shortly.</p>
        <p>
            Transaction: <strong>{{ $booking->transaction_number }}</strong><br>
            Requested departure: <strong>{{ $booking->rebooking_departure_date }}</strong><br>
            Requested return: <strong>{{ $booking->rebooking_return_date ?? 'One-way' }}</strong>
        </p>
        <p>We will send you a confirmation email once the rebooking is verified.</p>
    </body>
</html>
