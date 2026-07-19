<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BookingCreated extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public ?string $receiptPath;

    public function __construct(Booking $booking, ?string $receiptPath = null)
    {
        $this->booking = $booking;
        $this->receiptPath = $receiptPath;
    }

    public function build()
    {
        $mail = $this->subject('Amiga Gracia Travel Booking Received')
            ->view('emails.booking-created');

        if ($this->receiptPath) {
            if (file_exists($this->receiptPath)) {
                $mail->attach($this->receiptPath, [
                    'as' => 'receipt.pdf',
                    'mime' => 'application/pdf',
                ]);
            }
        }

        return $mail;
    }
}
