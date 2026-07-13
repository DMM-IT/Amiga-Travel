<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Proof Received</title>
    </head>
    <body style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937;">
        <h1>Proof Received</h1>
        <p>Hi {{ $transaction->booking->client_name }},</p>
        <p>Thanks for submitting your payment proof. Our admin team will verify your booking before sending your final confirmation and ticket.</p>
        <p>
            Booking transaction: <strong>{{ $transaction->booking->transaction_number }}</strong><br>
            Amount due: <strong>₱{{ number_format($transaction->booking->total_price, 2) }}</strong>
        </p>
        <p>We will notify you as soon as your booking has been confirmed.</p>
    </body>
</html>
