<?php

namespace App\Livewire;

use App\Models\Booking;
use Livewire\Component;

class BookingLookup extends Component
{
    public string $transaction_number = '';
    public ?Booking $booking = null;
    public bool $searched = false;

    public function search(): void
    {
        $this->validate([
            'transaction_number' => 'required|string',
        ]);

        $this->searched = true;

        $this->booking = Booking::with(['passengers.discount', 'accommodations', 'transaction'])
            ->where('transaction_number', trim($this->transaction_number))
            ->first();
    }

    public function render()
    {
        return view('livewire.booking-lookup');
    }
}
