<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingCancellation extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $refundDestination;

    public function __construct(Booking $booking, string $refundDestination)
    {
        $this->booking = $booking;
        $this->refundDestination = $refundDestination;
    }

    public function build(): self
    {
        return $this->subject('Your booking has been cancelled')
            ->view('emails.booking-cancellation');
    }
}
