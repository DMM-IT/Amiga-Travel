<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Storage;

class RebookingVerification extends Mailable
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
        $mail = $this->subject('Amiga Gracia Travel Rebooking Verified')
            ->view('emails.rebooking-verification');

        if ($this->receiptPath) {
            if ($this->receiptDisk && Storage::disk($this->receiptDisk)->exists($this->receiptPath)) {
                $mail->attachFromStorageDisk($this->receiptDisk, $this->receiptPath, 'rebooking-confirmation.pdf', [
                    'mime' => 'application/pdf',
                ]);
            } elseif (file_exists($this->receiptPath)) {
                $mail->attach($this->receiptPath, [
                    'as' => 'rebooking-confirmation.pdf',
                    'mime' => 'application/pdf',
                ]);
            }
        }

        return $mail;
    }
}
