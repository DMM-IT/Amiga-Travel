<?php

namespace App\Mail;

use App\Models\Booking;
use App\Models\ServiceCancellation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class ServiceCancellationNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public ServiceCancellation $cancellation
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: "IMPORTANT: Schedule Cancelled for Booking #{$this->booking->transaction_number} ({$this->cancellation->carrier})",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.service-cancellation-notification',
            with: [
                'rescheduleUrl' => route('booking.reschedule', ['transaction_number' => $this->booking->transaction_number]),
            ],
        );
    }
}
