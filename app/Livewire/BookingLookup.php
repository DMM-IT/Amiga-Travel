<?php

namespace App\Livewire;

use App\Mail\BookingCancellation;
use App\Mail\RebookingRequested;
use App\Models\Booking;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;

class BookingLookup extends Component
{
    use WithFileUploads;
    public string $transaction_number = '';
    public ?Booking $booking = null;
    public bool $searched = false;
    public ?string $feedback = null;
    public bool $cancellationRequested = false;
    public bool $cancellationWindowActive = false;
    public bool $cancellationExpired = false;
    public int $cancelCountdown = 300;
    public ?string $refund_destination = null;
    public bool $rebookingRequested = false;
    public bool $rebookingPaid = false;
    public bool $rebooking_is_round_trip = false;
    public $rebookingProof;
    public bool $isUploadingRebooking = false;
    public ?string $rebooking_departure_date = null;
    public ?string $rebooking_return_date = null;

    protected $rules = [
        'rebookingProof' => 'nullable|image|max:2048',
        'rebooking_departure_date' => 'required|date',
        'rebooking_is_round_trip' => 'boolean',
        'rebooking_return_date' => 'nullable|date|after_or_equal:rebooking_departure_date|required_if:rebooking_is_round_trip,1',
    ];

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
        $this->resetRebookingState();

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

        if (! $this->booking->canCancelOrRebook()) {
            $this->feedback = 'You cannot cancel this booking as the departure date has passed.';
            return;
        }

        if ($this->booking->status !== 'pending' || ! $this->booking->transaction || ! in_array($this->booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            $this->feedback = 'This booking cannot be cancelled because it has already been verified or completed.';

            return;
        }

        $this->resetRebookingState();
        $this->cancellationRequested = true;
        $this->cancellationWindowActive = false;
        $this->cancelCountdown = 300;
        $this->refund_destination = null;
        $this->feedback = 'Enter where you would like the refund sent. Cancellation fee is 50% of total price, you will receive a 50% refund.';

        $this->loadCancellationWindowFromSession();

        if ($this->cancellationWindowActive) {
            $this->feedback = 'Your 5-minute cancellation window is active. Confirm cancellation before it expires. Refund is 50% of total price.';
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

        if (! $this->booking->canCancelOrRebook()) {
            $this->feedback = 'You cannot cancel this booking as the departure date has passed.';
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

        $cancellationFee = $this->booking->getCancellationFeeAmount();
        $refundAmount = $this->booking->getRefundAmount();

        $this->booking->update([
            'status' => 'cancelled',
            'cancellation_fee' => $cancellationFee,
            'refund_amount' => $refundAmount,
            'refund_destination' => $this->refund_destination,
        ]);
        $this->booking->transaction->update(['payment_status' => 'cancelled']);
        $this->booking = $this->booking->fresh(['passengers.discount', 'accommodations', 'transaction']);

        Mail::to($this->booking->client_email)->send(new BookingCancellation($this->booking, $this->refund_destination));

        $this->feedback = "Your booking has been cancelled successfully. Cancellation fee: ₱" . number_format($cancellationFee, 2) . ", Refund amount: ₱" . number_format($refundAmount, 2) . ". A confirmation email has been sent.";
        $this->resetCancellationState();
    }

    public function cancelCancellationRequest(): void
    {
        $this->resetCancellationState();
        $this->feedback = 'Cancellation request cancelled. Your proof-upload timer will remain active if it has not yet expired.';
    }

    public function requestRebooking(): void
    {
        if (! $this->booking) {
            $this->feedback = 'Booking not found.';

            return;
        }

        if (! $this->booking->canCancelOrRebook()) {
            $this->feedback = 'You cannot rebook this booking as the departure date has passed.';
            return;
        }

        if ($this->booking->status !== 'pending' || ! $this->booking->transaction || ! in_array($this->booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            $this->feedback = 'This booking cannot be rebooked because it has already been verified or completed.';

            return;
        }

        $this->resetCancellationState();
        $this->rebookingRequested = true;
        $this->rebooking_is_round_trip = filled($this->booking->return_date);
        $this->rebooking_departure_date = $this->booking->departure_date?->format('Y-m-d');
        $this->rebooking_return_date = $this->booking->return_date?->format('Y-m-d');
        $this->feedback = "To rebook, please select your new travel dates and upload proof of payment for the 30% rebooking fee. Rebooking fee: ₱" . number_format($this->booking->getRebookingFeeAmount(), 2) . ".";
    }

    public function submitRebookingProof(): void
    {
        $this->validate();

        $this->isUploadingRebooking = true;

        $path = $this->rebookingProof->store('rebooking_proofs', 'public');

        $rebookingFee = $this->booking->getRebookingFeeAmount();

        $this->booking->transaction->update([
            'rebooking_fee' => $rebookingFee,
            'rebooking_proof_of_payment' => $path,
        ]);

        $this->booking->update([
            'rebooking_status' => 'pending',
            'rebooking_departure_date' => $this->rebooking_departure_date,
            'rebooking_return_date' => $this->rebooking_is_round_trip ? $this->rebooking_return_date : null,
        ]);

        Mail::to($this->booking->client_email)->send(new RebookingRequested($this->booking));

        $this->isUploadingRebooking = false;
        $this->rebookingPaid = true;

        $this->feedback = "Rebooking fee payment received and is now pending verification. Rebooking fee: ₱" . number_format($rebookingFee, 2) . ".";
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
        $this->cancellationExpired = false;
        $this->cancelCountdown = 300;
        $this->refund_destination = null;
    }

    private function resetRebookingState(): void
    {
        $this->rebookingRequested = false;
        $this->rebookingPaid = false;
        $this->rebooking_is_round_trip = false;
        $this->rebookingProof = null;
        $this->isUploadingRebooking = false;
        $this->rebooking_departure_date = null;
        $this->rebooking_return_date = null;
    }

    public function render()
    {
        return view('livewire.booking-lookup');
    }
}
