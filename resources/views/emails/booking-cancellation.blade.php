<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Cancellation</title>
</head>
<body style="font-family: Arial, sans-serif; color: #111827; line-height: 1.6;">
    <h2 style="color: #ee018d;">Your booking has been cancelled</h2>
    <p>Hi {{ $booking->client_name }},</p>
    <p>Your booking with transaction number <strong>{{ $booking->transaction_number }}</strong> has been successfully cancelled.</p>
    <p>The agency will send your refund to: <strong>{{ $refundDestination }}</strong>.</p>
    <p>If you did not request this cancellation, please contact our support team immediately.</p>
    <p>Thank you,<br>Amiga Gracia Travel</p>
</body>
</html>
