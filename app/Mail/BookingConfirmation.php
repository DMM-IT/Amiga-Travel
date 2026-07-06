<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public string $ticketUrl;
    public string $receiptPath;

    public function __construct(Booking $booking, string $ticketUrl, string $receiptPath)
    {
        $this->booking = $booking;
        $this->ticketUrl = $ticketUrl;
        $this->receiptPath = $receiptPath;
    }

    public function build()
    {
        return $this->subject('Amiga Gracia Travel Booking Confirmation')
            ->view('emails.booking-confirmation')
            ->attach($this->receiptPath, [
                'as' => 'receipt.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
