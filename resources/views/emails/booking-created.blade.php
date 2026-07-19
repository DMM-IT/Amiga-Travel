<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Booking Received</title>
    </head>
    <body style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937;">
        <h1>Booking Received</h1>
        <p>Hi {{ $booking->client_name }},</p>
        <p>Your booking request has been received and is now pending verification.</p>
        <p>
            Transaction: <strong>{{ $booking->transaction_number }}</strong><br>
            Origin: <strong>{{ $booking->origin }}</strong><br>
            Destination: <strong>{{ $booking->destination }}</strong><br>
            Departure: <strong>{{ $booking->departure_date }}</strong><br>
            Return: <strong>{{ $booking->return_date ?? 'One-way' }}</strong>
        </p>
        <p>We will notify you once your booking has been verified.</p>
    </body>
</html>
