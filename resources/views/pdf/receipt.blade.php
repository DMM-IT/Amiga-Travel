<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Receipt</title>
    <style>
        body { font-family: Arial, sans-serif; color: #1f2937; margin: 0; padding: 0; }
        .container { padding: 32px; }
        .header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 28px; }
        .brand { font-size: 24px; font-weight: 700; letter-spacing: 0.05em; }
        .tagline { color: #64748b; font-size: 14px; }
        .section { margin-bottom: 24px; }
        .section-title { font-size: 18px; font-weight: 700; margin-bottom: 12px; }
        .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px; }
        .info-box { border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px; }
        .info-box strong { display: block; margin-bottom: 6px; }
        .divider { border-top: 1px solid #e2e8f0; margin: 24px 0; }
        .footer { color: #64748b; font-size: 12px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div>
                <div class="brand">Amiga Gracia Travel Service</div>
                <div class="tagline">Booking Receipt</div>
            </div>
            <div>
                <strong>Transaction</strong>
                <div>{{ $booking->transaction_number }}</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Ferry schedule</div>
            @if($booking->schedule_summary)
                <div class="info-box">
                    <strong>{{ $booking->schedule_service }}</strong>
                    <div>{{ $booking->schedule_departure_time }} → {{ $booking->schedule_arrival_time }}</div>
                    <div>Per passenger: ₱{{ number_format($booking->schedule_price, 2) }}</div>
                </div>
            @else
                <div class="info-box">
                    <div>Schedule details not recorded for this booking.</div>
                </div>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Passengers</div>
            <div class="info-grid">
                <div class="info-box">
                    <strong>Client</strong>
                    <div>{{ $booking->client_name }}</div>
                    <div>{{ $booking->client_email }}</div>
                </div>
                <div class="info-box">
                    <strong>Travel</strong>
                    <div><strong>Origin:</strong> {{ $booking->origin }}</div>
                    <div><strong>Destination:</strong> {{ $booking->destination }}</div>
                </div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Travel dates</div>
            <div class="info-grid">
                <div class="info-box">
                    <strong>Departure</strong>
                    <div>{{ $booking->departure_date }}</div>
                </div>
                <div class="info-box">
                    <strong>Return</strong>
                    <div>{{ $booking->return_date ?? 'One-way' }}</div>
                </div>
            </div>
        </div>

        @if($booking->scheduleAccommodation)
            <div class="section">
                <div class="section-title">Accommodation</div>
                <div class="info-box">
                    <strong>{{ $booking->scheduleAccommodation->name }}</strong>
                    @if($booking->scheduleAccommodation->description)
                        <div>{{ $booking->scheduleAccommodation->description }}</div>
                    @endif
                    <div>Price: ₱{{ number_format($booking->scheduleAccommodation->price, 2) }}</div>
                </div>
            </div>
        @endif

        @if($booking->accommodations->count() > 0)
            <div class="section">
                <div class="section-title">Additional Accommodations</div>
                @foreach($booking->accommodations as $accommodation)
                    <div class="info-box" style="margin-bottom: 10px;">
                        <strong>{{ $accommodation->name }}</strong>
                        <div>Price: ₱{{ number_format($accommodation->price, 2) }}</div>
                    </div>
                @endforeach
            </div>
        @endif

        <div class="section">
            <div class="section-title">Summary</div>
            <div class="info-box">
                @if($booking->voucher_code)
                    <div style="margin-bottom: 8px;">
                        <strong>Voucher:</strong> {{ $booking->voucher_code }}<br>
                        <strong>Discount:</strong> -₱{{ number_format($booking->voucher_discount_amount, 2) }}
                    </div>
                    <div style="margin-bottom: 8px;">
                        <strong>Subtotal before voucher:</strong> ₱{{ number_format($booking->subtotal_before_voucher, 2) }}
                    </div>
                @endif
                <strong>Total Price</strong>
                <div>₱{{ number_format($booking->total_price, 2) }}</div>
            </div>
        </div>

        <div class="divider"></div>

        <div class="footer">
            Thank you for booking with Amiga Gracia Travel Service. Please keep this receipt for your reference.
        </div>
    </div>
</body>
</html>
