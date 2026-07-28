<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>{{ !empty($isResumption) ? 'Travel Operations Resuming Notice' : 'Schedule Cancellation Notice' }}</title>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; background-color: #f8fafc; color: #1e293b; margin: 0; padding: 20px; }
        .card { max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 16px; border: 1px solid #e2e8f0; padding: 32px; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05); }
        .header { text-align: center; border-bottom: 1px solid #f1f5f9; padding-bottom: 20px; margin-bottom: 24px; }
        .alert-badge-red { display: inline-block; background: #fef2f2; border: 1px solid #fecaca; color: #dc2626; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 6px 12px; border-radius: 20px; letter-spacing: 0.5px; }
        .alert-badge-green { display: inline-block; background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; font-size: 12px; font-weight: 700; text-transform: uppercase; padding: 6px 12px; border-radius: 20px; letter-spacing: 0.5px; }
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
            @if(!empty($isResumption))
                <span class="alert-badge-green">🟢 Travel Operations Resumed — Action Required</span>
                <h1>Good News! Travel Operations Resuming</h1>
            @else
                <span class="alert-badge-red">🔴 Unavoidable Schedule Disruption Notice</span>
                <h1>Your Flight / Voyage Has Been Disrupted</h1>
            @endif
            <p style="color: #64748b; font-size: 14px; margin: 4px 0 0 0;">Amiga Gracia Travel Services Notification</p>
        </div>

        <p>Dear <strong>{{ $booking->client_name }}</strong>,</p>
        
        @php
            $carrierName = $cancellation->carrier 
                ?? $cancellation->ferryRoute?->operator 
                ?? $cancellation->vehicle?->operator 
                ?? 'the operator';
        @endphp

        @if(!empty($isResumption))
            <p>We are pleased to inform you that travel operations for <strong>{{ $carrierName }}</strong> are officially resuming starting <strong>{{ $cancellation->resume_date ? $cancellation->resume_date->format('F d, Y') : 'soon' }}</strong>. You can now log in to select your replacement travel date at zero extra cost.</p>
        @else
            <p>We regret to inform you that your upcoming travel schedule for <strong>Booking #{{ $booking->transaction_number }}</strong> has been cancelled by the operator due to <strong>{{ ucfirst(str_replace('_', ' ', $cancellation->reason_category)) }}</strong>.</p>
        @endif

        <div class="info-grid">
            <div class="info-row">
                <span class="label">Booking Reference</span>
                <span class="value">#{{ $booking->transaction_number }}</span>
            </div>
            <div class="info-row">
                <span class="label">Carrier / Operator</span>
                <span class="value">{{ $carrierName }}</span>
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
                <span class="label">Official Resume Date</span>
                @if($cancellation->resume_date)
                    <span class="value" style="color: #216417;">{{ $cancellation->resume_date->format('F d, Y') }}</span>
                @else
                    <span class="value" style="color: #ea580c;">To Be Announced (Suspended)</span>
                @endif
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
            @if($cancellation->resume_date)
                &bull; <strong>Select Replacement Option:</strong> You can select a replacement schedule departing on or after <strong>{{ $cancellation->resume_date->format('F d, Y') }}</strong>.
            @else
                &bull; <strong>Service Status Notice:</strong> Operations are temporarily suspended. We will send an email notification to your inbox the moment travel clearance is granted and service resumes.
            @endif
        </p>

        <div class="btn-container">
            @if(!empty($isResumption))
                <a href="{{ $rescheduleUrl }}" class="btn">Select Replacement Travel Date &rarr;</a>
            @else
                <a href="{{ $rescheduleUrl }}" class="btn">View Booking & Reschedule Status &rarr;</a>
            @endif
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
