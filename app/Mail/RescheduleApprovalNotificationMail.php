<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class RescheduleApprovalNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Booking $booking,
        public bool $approved,
        public ?string $staffNote = null
    ) {}

    public function envelope(): Envelope
    {
        $status = $this->approved ? 'APPROVED' : 'ACTION REQUIRED';

        return new Envelope(
            subject: "Reschedule Request {$status} - Booking #{$this->booking->transaction_number}",
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.reschedule-approval-notification',
            with: [
                'statusUrl' => route('book.status', ['transaction_number' => $this->booking->transaction_number]),
            ],
        );
    }
}
