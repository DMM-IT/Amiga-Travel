<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class BookingConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;
    public ?string $ticketUrl;
    public ?string $receiptPath;
    public ?string $receiptDisk;

    public function __construct(Booking $booking, ?string $ticketUrl = null, ?string $receiptPath = null, ?string $receiptDisk = null)
    {
        $this->booking = $booking;
        $this->ticketUrl = $ticketUrl;
        $this->receiptPath = $receiptPath;
        $this->receiptDisk = $receiptDisk;
    }

    public function build()
    {
        $mail = $this->subject('Amiga Gracia Travel Booking Confirmation')
            ->view('emails.booking-confirmation');

        if ($this->receiptPath) {
            if ($this->receiptDisk && Storage::disk($this->receiptDisk)->exists($this->receiptPath)) {
                $mail->attachFromStorageDisk($this->receiptDisk, $this->receiptPath, 'receipt.pdf', [
                    'mime' => 'application/pdf',
                ]);
            } elseif (file_exists($this->receiptPath)) {
                $mail->attach($this->receiptPath, [
                    'as' => 'receipt.pdf',
                    'mime' => 'application/pdf',
                ]);
            }
        }

        return $mail;
    }
}
