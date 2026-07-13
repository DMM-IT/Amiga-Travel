<?php

namespace App\Livewire;

use App\Mail\BookingCancellation;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;

class BookingLookup extends Component
{
    public string $transaction_number = '';
    public ?Booking $booking = null;
    public bool $searched = false;
    public ?string $feedback = null;
    public bool $cancellationRequested = false;
    public bool $cancellationWindowActive = false;
    public bool $cancellationExpired = false;
    public int $cancelCountdown = 300;
    public ?string $refund_destination = null;

    public function mount(): void
    {
        $transactionNumber = request()->query('transaction_number');

        if (filled($transactionNumber)) {
            $this->transaction_number = trim($transactionNumber);
            $this->search();
            // If the link included start_cancellation=1, begin the cancellation flow and start the window.
            if (request()->query('start_cancellation')) {
                $this->requestCancellation();
            }
            $this->loadCancellationWindowFromSession();
        }
    }

    public function search(): void
    {
        $this->validate([
            'transaction_number' => 'required|string',
        ]);

        $this->searched = true;
        $this->feedback = null;
        $this->resetCancellationState();

        $this->booking = Booking::with(['passengers.discount', 'accommodations', 'transaction'])
            ->where('transaction_number', trim($this->transaction_number))
            ->first();

        $this->loadCancellationWindowFromSession();
    }

    public function requestCancellation(): void
    {
        if (! $this->booking) {
            $this->feedback = 'Booking not found.';

            return;
        }

        if ($this->booking->status !== 'pending' || ! $this->booking->transaction || ! in_array($this->booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            $this->feedback = 'This booking cannot be cancelled because it has already been verified or completed.';

            return;
        }

        $this->cancellationRequested = true;
        $this->cancellationWindowActive = false;
        $this->cancelCountdown = 300;
        $this->refund_destination = null;
        $this->feedback = 'Enter where you would like the refund sent. The 5-minute cancellation window begins when proof is uploaded.';

        $this->loadCancellationWindowFromSession();

        if ($this->cancellationWindowActive) {
            $this->feedback = 'Your 5-minute cancellation window is active. Confirm cancellation before it expires.';
        }
    }

    public function cancelBooking(): void
    {
        $this->requestCancellation();
    }

    public function tickCancelCountdown(): void
    {
        if (! $this->cancellationRequested || ! $this->cancellationWindowActive) {
            return;
        }

        $this->cancelCountdown = max(0, $this->cancelCountdown - 1);

        if ($this->cancelCountdown === 0) {
            $this->feedback = 'Cancellation window has expired. Please start again if you still wish to cancel.';
            $this->resetCancellationState();
            // mark expired so UI can disable further attempts for this session
            $this->cancellationExpired = true;
            // clear session expiry key
            $key = 'cancellation_window_expires_for_' . $this->transaction_number;
            session()->forget($key);
        }
    }

    public function startCancellationWindow(): void
    {
        if (! $this->cancellationRequested) {
            return;
        }

        $this->cancellationWindowActive = true;
        $this->cancelCountdown = 300;
        $this->feedback = 'Cancellation timer started. You have 5 minutes to confirm the cancellation.';
        // persist expiry in session so navigation doesn't lose it
        $key = 'cancellation_window_expires_for_' . $this->transaction_number;
        session([$key => now()->addSeconds($this->cancelCountdown)->timestamp]);
    }

    public function confirmCancellation(): void
    {
        $this->validate([
            'refund_destination' => 'required|string|max:255',
        ]);

        if (! $this->booking) {
            $this->feedback = 'Booking not found.';

            return;
        }

        if ($this->booking->status !== 'pending' || ! $this->booking->transaction || ! in_array($this->booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            $this->feedback = 'This booking cannot be cancelled because it has already been verified or completed.';

            return;
        }

        if (! $this->cancellationWindowActive || $this->cancelCountdown === 0) {
            $this->feedback = 'Cancellation window is not active. Click Done to start the 5-minute confirmation window.';

            return;
        }

        $this->booking->update(['status' => 'cancelled']);
        $this->booking->transaction->update(['payment_status' => 'cancelled']);
        $this->booking = $this->booking->fresh(['passengers.discount', 'accommodations', 'transaction']);

        Mail::to($this->booking->client_email)->send(new BookingCancellation($this->booking, $this->refund_destination));

        $this->feedback = 'Your booking has been cancelled successfully. A confirmation email has been sent.';
        $this->resetCancellationState();
    }

    public function cancelCancellationRequest(): void
    {
        $this->resetCancellationState();
        $this->feedback = 'Cancellation request cancelled. Your proof-upload timer will remain active if it has not yet expired.';
    }

    private function getCancellationSessionKey(): string
    {
        return 'cancellation_window_expires_for_' . $this->transaction_number;
    }

    private function loadCancellationWindowFromSession(): void
    {
        $key = $this->getCancellationSessionKey();
        $expires = session($key);

        if (! $expires) {
            return;
        }

        if (now()->timestamp >= $expires) {
            $this->cancellationExpired = true;
            session()->forget($key);
            $this->resetCancellationState();
            $this->feedback = 'The cancellation window has expired. Upload proof again to start a new 5-minute window.';
            return;
        }

        $this->cancellationRequested = true;
        $this->cancellationWindowActive = true;
        $this->cancelCountdown = max(0, $expires - now()->timestamp);
    }

    private function resetCancellationState(): void
    {
        $this->cancellationRequested = false;
        $this->cancellationWindowActive = false;
        $this->cancelCountdown = 300;
        $this->refund_destination = null;
    }

    public function render()
    {
        return view('livewire.booking-lookup');
    }
}
