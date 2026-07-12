<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;

class BookingLookup extends Component
{
    public string $transaction_number = '';
    public ?Booking $booking = null;
    public bool $searched = false;
    public ?string $feedback = null;

    public function mount(): void
    {
        $transactionNumber = request()->query('transaction_number');

        if (filled($transactionNumber)) {
            $this->transaction_number = trim($transactionNumber);
            $this->search();
        }
    }

    public function search(): void
    {
        $this->validate([
            'transaction_number' => 'required|string',
        ]);

        $this->searched = true;
        $this->feedback = null;

        $this->booking = Booking::with(['passengers.discount', 'accommodations', 'transaction'])
            ->where('transaction_number', trim($this->transaction_number))
            ->first();
    }

    public function cancelBooking(): void
    {
        $this->validate([
            'transaction_number' => 'required|string',
        ]);

        if (! $this->booking) {
            $this->feedback = 'Booking not found.';

            return;
        }

        if ($this->booking->status !== 'pending' || ! $this->booking->transaction || ! in_array($this->booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            $this->feedback = 'This booking cannot be cancelled because it has already been verified or completed.';

            return;
        }

        $this->booking->update(['status' => 'cancelled']);
        $this->booking->transaction->update(['payment_status' => 'cancelled']);
        $this->booking = $this->booking->fresh(['passengers.discount', 'accommodations', 'transaction']);
        $this->feedback = 'Your booking has been cancelled successfully.';
    }

    public function render()
    {
        return view('livewire.booking-lookup');
    }
}
