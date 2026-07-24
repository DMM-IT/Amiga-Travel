<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reschedule Status Update</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { text-align: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 24px; }
        .badge-success { display: inline-block; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 6px 12px; border-radius: 20px; }
        .badge-warning { display: inline-block; background: #fefce8; border: 1px solid #fef08a; color: #ca8a04; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 6px 12px; border-radius: 20px; }
        h1 { font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 12px; margin-bottom: 4px; }
        .info-grid { background: #f8fafc; border-radius: 12px; padding: 20px; margin: 20px 0; border: 1px solid #f1f5f9; }
        .info-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #e2e8f0; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #64748b; font-weight: 600; }
        .value { color: #0f172a; font-weight: 700; text-align: right; }
        .note-box { background: #f1f5f9; border-left: 4px solid #64748b; padding: 14px; border-radius: 8px; margin: 20px 0; font-size: 14px; color: #334155; }
        .btn-container { text-align: center; margin: 32px 0 20px 0; }
        .btn { display: inline-block; background: #216417; color: #ffffff !important; text-decoration: none; font-weight: 700; font-size: 15px; padding: 14px 28px; border-radius: 12px; }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 32px; border-top: 1px solid #f1f5f9; pt-16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            @if($approved)
                <span class="badge-success">&check; Reschedule Approved</span>
                <h1>Your New Travel Date is Confirmed!</h1>
            @else
                <span class="badge-warning">&excl; Action Required</span>
                <h1>Reschedule Request Declined</h1>
            @endif
            <p style="color: #64748b; font-size: 14px; margin: 4px 0 0 0;">Amiga Gracia Travel Services</p>
        </div>

        <p>Dear <strong>{{ $booking->client_name }}</strong>,</p>

        @if($approved)
            <p>Great news! Our team has reviewed and approved your replacement travel schedule for <strong>Booking #{{ $booking->transaction_number }}</strong>. Your ticket is now updated at <strong>₱0 additional cost</strong>.</p>
            
            <div class="info-grid">
                <div class="info-row">
                    <span class="label">Booking Reference</span>
                    <span class="value">#{{ $booking->transaction_number }}</span>
                </div>
                <div class="info-row">
                    <span class="label">New Departure Date</span>
                    <span class="value" style="color: #216417;">{{ $booking->departure_date->format('F d, Y') }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Service</span>
                    <span class="value">{{ $booking->schedule_service }}</span>
                </div>
                <div class="info-row">
                    <span class="label">Departure Time</span>
                    <span class="value">{{ $booking->schedule_departure_time }}</span>
                </div>
            </div>
        @else
            <p>Your requested replacement schedule for <strong>Booking #{{ $booking->transaction_number }}</strong> could not be approved at this time.</p>
        @endif

        @if($staffNote)
            <div class="note-box">
                <strong>Staff Note:</strong><br>
                {{ $staffNote }}
            </div>
        @endif

        <div class="btn-container">
            <a href="{{ $statusUrl }}" class="btn">View Updated Ticket Details &rarr;</a>
        </div>

        <div class="footer">
            &copy; {{ date('Y') }} Amiga Gracia Travel Services. All rights reserved.
        </div>
    </div>
</body>
</html>
