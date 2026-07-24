<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Schedule Cancellation Notice</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { text-align: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 24px; }
        .alert-badge { display: inline-block; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 6px 12px; border-radius: 20px; letter-spacing: 0.5px; }
        h1 { font-size: 20px; font-weight: 800; color: #0f172a; margin-top: 12px; margin-bottom: 4px; }
        .info-grid { background: #f8fafc; border-radius: 12px; padding: 20px; margin: 20px 0; border: 1px solid #f1f5f9; }
        .info-row { display: flex; justify-content: space-between; padding: 6px 0; border-bottom: 1px dashed #e2e8f0; font-size: 14px; }
        .info-row:last-child { border-bottom: none; }
        .label { color: #64748b; font-weight: 600; }
        .value { color: #0f172a; font-weight: 700; text-align: right; }
        .message-box { background: #fff7ed; border-left: 4px solid #f97316; padding: 16px; border-radius: 8px; margin: 24px 0; font-size: 14px; color: #9a3412; }
        .btn-container { text-align: center; margin: 32px 0 20px 0; }
        .btn { display: inline-block; background: #216417; color: #ffffff !important; text-decoration: none; font-weight: 700; font-size: 15px; padding: 14px 28px; border-radius: 12px; box-shadow: 0 4px 12px rgba(33, 100, 23, 0.25); }
        .footer { text-align: center; font-size: 12px; color: #94a3b8; margin-top: 32px; border-top: 1px solid #f1f5f9; pt-16px; }
    </style>
</head>
<body>
    <div class="card">
        <div class="header">
            <span class="alert-badge">Notice of Unavoidable Service Cancellation</span>
            <h1>Your Flight / Voyage Has Been Disrupted</h1>
            <p style="color: #64748b; font-size: 14px; margin: 4px 0 0 0;">Amiga Gracia Travel Services Notification</p>
        </div>

        <p>Dear <strong>{{ $booking->client_name }}</strong>,</p>
        
        <p>We regret to inform you that your upcoming travel schedule for <strong>Booking #{{ $booking->transaction_number }}</strong> has been cancelled by the operator due to <strong>{{ ucfirst(str_replace('_', ' ', $cancellation->reason_category)) }}</strong>.</p>

        <div class="info-grid">
            <div class="info-row">
                <span class="label">Booking Reference</span>
                <span class="value">#{{ $booking->transaction_number }}</span>
            </div>
            <div class="info-row">
                <span class="label">Carrier / Operator</span>
                <span class="value">{{ $cancellation->carrier }}</span>
            </div>
            <div class="info-row">
                <span class="label">Original Route</span>
                <span class="value">{{ $booking->origin }} &rarr; {{ $booking->destination }}</span>
            </div>
            <div class="info-row">
                <span class="label">Original Travel Date</span>
                <span class="value">{{ $booking->departure_date->format('F d, Y') }}</span>
            </div>
            <div class="info-row">
                <span class="label">Service Resume Date</span>
                <span class="value" style="color: #216417;">{{ $cancellation->resume_date->format('F d, Y') }}</span>
            </div>
        </div>

        <div class="message-box">
            <strong>Carrier / Staff Notice:</strong><br>
            {{ $cancellation->customer_message }}
        </div>

        <p style="font-size: 14px; line-height: 1.6;">
            <strong>Important Policy Information:</strong><br>
            &bull; <strong>No Rescheduling Fee:</strong> You will not be charged any fees or fare differences for choosing a replacement date.<br>
            &bull; <strong>Original Details Preserved:</strong> Your route, passenger details, and payment remain securely on file.<br>
            &bull; <strong>Select Replacement Option:</strong> You can select a replacement schedule departing on or after <strong>{{ $cancellation->resume_date->format('F d, Y') }}</strong>.
        </p>

        <div class="btn-container">
            <a href="{{ $rescheduleUrl }}" class="btn">Choose New Travel Date &rarr;</a>
        </div>

        <p style="text-align: center; font-size: 13px; color: #64748b;">
            If none of the available replacement dates fit your schedule, please click the button above to request support from our team.
        </p>

        <div class="footer">
            &copy; {{ date('Y') }} Amiga Gracia Travel Services. All rights reserved.
        </div>
    </div>
</body>
</html>
