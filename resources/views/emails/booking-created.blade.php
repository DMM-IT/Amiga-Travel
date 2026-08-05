<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <title>Booking Received</title>
    </head>
    <body style="font-family:Arial,sans-serif;line-height:1.6;color:#1f2937;">
        <h1>Payment Reminder for Your Booking</h1>
        <p>Hi {{ $booking->client_name }},</p>
        <p>Your booking request has been received. Please note that your booking is currently <strong>unpaid</strong>.</p>
        <p>
            Transaction: <strong>{{ $booking->transaction_number }}</strong><br>
            Origin: <strong>{{ $booking->origin }}</strong><br>
            Destination: <strong>{{ $booking->destination }}</strong><br>
            Departure: <strong>{{ $booking->departure_date }}</strong><br>
            Return: <strong>{{ $booking->return_date ?? 'One-way' }}</strong>
            @if($booking->voucher_code)
                <br>Voucher: <strong>{{ $booking->voucher_code }}</strong>
                <br>Discount: <strong>-₱{{ number_format($booking->voucher_discount_amount, 2) }}</strong>
                <br>Subtotal before voucher: <strong>₱{{ number_format($booking->subtotal_before_voucher, 2) }}</strong>
            @endif
            <br>Total Price: <strong>₱{{ number_format($booking->total_price, 2) }}</strong>
        </p>
        
        <p style="color: #dc2626; font-size: 1.1em;">
            <strong>REMINDER: Your booking will be automatically cancelled if payment is not received within 1 hour.</strong>
        </p>
    </body>
</html>
