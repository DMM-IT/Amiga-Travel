<?php

namespace App\Livewire;

use App\Models\Booking;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class UserDashboard extends Component
{
    public $bookings;

    public function mount()
    {
        $this->bookings = Booking::with(['transaction', 'passengers', 'accommodations'])
            ->where('client_email', Auth::user()->email)
            ->orderBy('created_at', 'desc')
            ->get();
    }

    public function render()
    {
        return view('livewire.user-dashboard');
    }
}
