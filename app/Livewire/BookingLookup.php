<?php

namespace App\Livewire;

use App\Mail\BookingCancellation;
use App\Mail\RebookingRequested;
use App\Models\Booking;
use App\Models\Transaction;
use App\Models\Schedule;
use Illuminate\Support\Facades\Mail;
use Livewire\Component;
use Livewire\WithFileUploads;
use Illuminate\Support\Facades\Log;
use Throwable;

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
    public string $refund_method = 'GCash';
    public string $refund_bank_name = '';
    public string $refund_account_number = '';
    public string $refund_account_name = '';
    public bool $rebookingRequested = false;
    public bool $rebookingPaid = false;
    public bool $rebooking_is_round_trip = false;
    public $rebookingProof;
    public bool $isUploadingRebooking = false;
    public ?string $rebooking_departure_date = null;
    public ?string $rebooking_return_date = null;

    // Customer Rebooking Wizard State
    public string $rebooking_step = 'departure_date';

    // Departure Leg Selection
    public ?int $rebooking_dep_schedule_id = null;
    public ?string $rebooking_dep_accommodation_id = null;
    public ?float $rebooking_dep_schedule_price = null;
    public ?float $rebooking_dep_accommodation_price = null;

    // Return Leg Selection
    public ?int $rebooking_ret_schedule_id = null;
    public ?string $rebooking_ret_accommodation_id = null;
    public ?float $rebooking_ret_schedule_price = null;
    public ?float $rebooking_ret_accommodation_price = null;

    // Price computation (before and after booking)
    public float $rebooking_new_total = 0.0;
    public float $rebooking_price_diff = 0.0;
    public float $rebooking_total_to_pay = 0.0;

    public bool $showCancellationWarning = false;
    public bool $showRebookingWarning = false;
    public bool $showCancellationReminder = false;
    public array $availableRebookingDates = [];
    public array $availableRebookingReturnDates = [];

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
            if (request()->query('show_cancellation_reminder')) {
                $this->showCancellationReminder = true;
            }
            $this->loadCancellationWindowFromSession();

            if ($this->cancellationExpired) {
                $this->showCancellationWarning = false;
                $this->showCancellationReminder = false;
            }
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

        $transactionNumber = trim($this->transaction_number);

        $this->booking = Booking::with(['passengers.discount', 'accommodations', 'transaction'])
            ->where('transaction_number', $transactionNumber)
            ->first();

        if (! $this->booking && ctype_digit($transactionNumber)) {
            $transaction = Transaction::with('booking.passengers.discount', 'booking.accommodations', 'booking.transaction')
                ->find($transactionNumber);

            if ($transaction && $transaction->booking) {
                $this->booking = $transaction->booking;
            }
        }

        $this->loadCancellationWindowFromSession();

        if ($this->cancellationExpired) {
            $this->showCancellationWarning = false;
            $this->showCancellationReminder = false;
        }
    }

    public function showCancellationWarning(): void
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
        $this->showCancellationWarning = true;
        $this->feedback = 'Please confirm that you want to start cancellation. This will begin a 5-minute confirmation timer and lock in a 50% refund.';
    }    public function requestCancellation(): void
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
        $this->showCancellationWarning = false;
        $this->showCancellationReminder = false;

        $this->cancellationRequested = true;
        $this->cancellationWindowActive = true;

        $expiresAt = $this->booking->created_at->addMinutes(5);
        $remaining = $expiresAt->timestamp - now()->timestamp;

        if ($remaining <= 0) {
            $this->cancellationExpired = true;
            $this->cancelCountdown = 0;
            $this->feedback = 'Cancellation is eligible for a 50% refund.';
        } else {
            $this->cancellationExpired = false;
            $this->cancelCountdown = $remaining;
            $this->feedback = 'Enter where you would like the refund sent. Cancellation is eligible for a 100% refund within 5 minutes of booking.';
        }

        $this->refund_destination = null;
        $this->refund_method = 'GCash';
        $this->refund_bank_name = '';
        $this->refund_account_number = '';
        $this->refund_account_name = '';
    }

    public function dismissCancellationReminder(): void
    {
        $this->showCancellationReminder = false;
    }

    public function confirmCancellationRequest(): void
    {
        $this->requestCancellation();
    }

    public function cancelBooking(): void
    {
        $this->requestCancellation();
    }

    public function confirmCancellation(): void
    {
        if (blank($this->refund_account_number) && blank($this->refund_account_name) && filled($this->refund_destination)) {
            // Fallback for tests that set refund_destination directly
            $this->validate([
                'refund_destination' => 'required|string|max:255',
            ]);
        } else {
            // Compile the refund destination string
            $destinationParts = [];
            $destinationParts[] = "Method: " . $this->refund_method;
            if (in_array($this->refund_method, ['Bank Account', 'Online Wallet'], true)) {
                $destinationParts[] = "Institution: " . $this->refund_bank_name;
            }
            $destinationParts[] = "Account No: " . $this->refund_account_number;
            $destinationParts[] = "Name: " . $this->refund_account_name;
            $this->refund_destination = implode(' | ', $destinationParts);

            // Perform validation
            $rules = [
                'refund_method' => 'required|string|in:GCash,Online Wallet,Bank Account',
                'refund_account_number' => 'required|string|max:50',
                'refund_account_name' => 'required|string|max:100',
            ];

            if (in_array($this->refund_method, ['Bank Account', 'Online Wallet'], true)) {
                $rules['refund_bank_name'] = 'required|string|max:100';
            }

            $this->validate($rules);
        }

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

        // Calculate refund percentage based on creation time:
        $isWithinFiveMinutes = $this->booking->created_at->addMinutes(5)->isFuture();
        if ($isWithinFiveMinutes) {
            $cancellationFee = 0.0;
            $refundAmount = $this->booking->total_price;
        } else {
            $cancellationFee = $this->booking->total_price * 0.5;
            $refundAmount = $this->booking->total_price * 0.5;
        }

        $this->booking->update([
            'status' => 'cancelled',
            'cancellation_fee' => $cancellationFee,
            'refund_amount' => $refundAmount,
            'refund_destination' => $this->refund_destination,
        ]);
        $this->booking->transaction->update(['payment_status' => 'cancelled']);
        $this->booking = $this->booking->fresh(['passengers.discount', 'accommodations', 'transaction']);

        try {
            Mail::to($this->booking->client_email)->send(new BookingCancellation($this->booking, $this->refund_destination));
        } catch (Throwable $e) {
            Log::error('Failed sending booking cancellation email', [
                'booking_id' => $this->booking->id ?? null,
                'email' => $this->booking->client_email ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        $this->feedback = "Your booking has been cancelled successfully. Cancellation fee: ₱" . number_format($cancellationFee, 2) . ", Refund amount: ₱" . number_format($refundAmount, 2) . ". A confirmation email has been sent.";
        $this->resetCancellationState();
    }

    public function tickCancelCountdown(): void
    {
        if (! $this->booking) {
            return;
        }

        $remaining = $this->booking->created_at->addMinutes(5)->timestamp - now()->timestamp;

        if ($remaining <= 0) {
            $this->cancelCountdown = 0;
            $this->cancellationExpired = true;
        } else {
            $this->cancelCountdown = $remaining;
            $this->cancellationExpired = false;
        }
    }

    public function cancelCancellationRequest(): void
    {
        $this->resetCancellationState();
        $this->feedback = 'Cancellation request cancelled. Your proof-upload timer will remain active if it has not yet expired.';
    }

    public function cancelRebookingWarning(): void
    {
        $this->showRebookingWarning = false;
        $this->feedback = null;
    }

    public function showRebookingWarning(): void
    {
        if (! $this->booking) {
            $this->feedback = 'Booking not found.';
            return;
        }

        if (! $this->booking->canCancelOrRebook()) {
            $this->feedback = 'You cannot rebook this booking as the departure date has passed.';
            return;
        }

        if ($this->booking->departure_date->isToday()) {
            $this->feedback = 'Rebooking cannot be requested for same-day departures. Please contact support for urgent changes.';
            return;
        }

        if ($this->booking->status !== 'pending' || ! $this->booking->transaction || ! in_array($this->booking->transaction->payment_status, ['pending', 'unpaid'], true)) {
            $this->feedback = 'This booking cannot be rebooked because it has already been verified or completed.';
            return;
        }

        $this->resetCancellationState();
        $this->showRebookingWarning = true;
        $this->feedback = 'Please confirm that you want to start rebooking. Rebooking requires a new travel date selection and proof of payment for the 30% fee.';
    }

    public function requestRebooking(): void
    {
        $this->showRebookingWarning();
    }

    public function confirmRebookingRequest(): void
    {
        $this->resetCancellationState();
        $this->resetRebookingState();
        $this->showRebookingWarning = false;
        $this->rebookingRequested = true;
        $this->rebooking_is_round_trip = filled($this->booking->return_date);
        $this->rebooking_departure_date = $this->booking->departure_date?->format('Y-m-d');
        $this->rebooking_return_date = $this->booking->return_date?->format('Y-m-d');
        $this->rebooking_step = 'departure_date';
        $this->feedback = "Please select your new travel date, schedule, and preferred accommodation below.";
    }

    public function setRebookingStep(string $step): void
    {
        $this->rebooking_step = $step;
        $this->feedback = null;
        if ($step === 'confirm') {
            $this->calculateRebookingPriceDiff();
        }
    }

    public function updatedRebookingDepartureDate(): void
    {
        $this->rebooking_dep_schedule_id = null;
        $this->rebooking_dep_accommodation_id = null;
        $this->rebooking_dep_schedule_price = null;
        $this->rebooking_dep_accommodation_price = null;
    }

    public function updatedRebookingReturnDate(): void
    {
        $this->rebooking_ret_schedule_id = null;
        $this->rebooking_ret_accommodation_id = null;
        $this->rebooking_ret_schedule_price = null;
        $this->rebooking_ret_accommodation_price = null;
    }

    public function selectRebookingDepartureSchedule(int $scheduleId, float $price): void
    {
        $this->rebooking_dep_schedule_id = $scheduleId;
        $this->rebooking_dep_schedule_price = $price;
        $this->rebooking_dep_accommodation_id = null;
        $this->rebooking_dep_accommodation_price = null;
        $this->setRebookingStep('departure_accommodation');
    }

    public function selectRebookingDepartureAccommodation(string $accId, float $price): void
    {
        $this->rebooking_dep_accommodation_id = $accId;
        $this->rebooking_dep_accommodation_price = $price;
        if ($this->rebooking_is_round_trip) {
            $this->setRebookingStep('return_date');
        } else {
            $this->setRebookingStep('confirm');
        }
    }

    public function selectRebookingReturnSchedule(int $scheduleId, float $price): void
    {
        $this->rebooking_ret_schedule_id = $scheduleId;
        $this->rebooking_ret_schedule_price = $price;
        $this->rebooking_ret_accommodation_id = null;
        $this->rebooking_ret_accommodation_price = null;
        $this->setRebookingStep('return_accommodation');
    }

    public function selectRebookingReturnAccommodation(string $accId, float $price): void
    {
        $this->rebooking_ret_accommodation_id = $accId;
        $this->rebooking_ret_accommodation_price = $price;
        $this->setRebookingStep('confirm');
    }

    public function getAvailableRebookingDepartureSchedulesProperty()
    {
        if (!$this->booking || !$this->rebooking_departure_date) return collect();
        return Schedule::forRouteAndDate($this->booking->origin, $this->booking->destination, $this->rebooking_departure_date)
            ->with(['ferryRoute', 'vehicle'])
            ->get();
    }

    public function getAvailableRebookingReturnSchedulesProperty()
    {
        if (!$this->booking || !$this->rebooking_return_date) return collect();
        return Schedule::forRouteAndDate($this->booking->destination, $this->booking->origin, $this->rebooking_return_date)
            ->with(['ferryRoute', 'vehicle'])
            ->get();
    }

    public function getRebookingDepartureAccommodationsProperty()
    {
        if (!$this->rebooking_dep_schedule_id) return collect();
        $schedule = Schedule::with(['scheduleAccommodations', 'transportClasses'])->find($this->rebooking_dep_schedule_id);
        if (!$schedule) return collect();

        $items = collect();
        foreach ($schedule->scheduleAccommodations->where('is_active', true)->sortBy('sort_order') as $acc) {
            $items->push((object)[
                'id' => 'acc_' . $acc->id,
                'name' => $acc->name,
                'description' => $acc->description,
                'price' => $acc->price,
            ]);
        }
        foreach ($schedule->transportClasses->where('pivot.is_active', true)->sortBy('pivot.sort_order') as $tc) {
            $items->push((object)[
                'id' => 'tc_' . $tc->id,
                'name' => $tc->name,
                'description' => $tc->pivot->description ?? $tc->description,
                'price' => $tc->pivot->additional_price,
            ]);
        }
        return $items;
    }

    public function getRebookingReturnAccommodationsProperty()
    {
        if (!$this->rebooking_ret_schedule_id) return collect();
        $schedule = Schedule::with(['scheduleAccommodations', 'transportClasses'])->find($this->rebooking_ret_schedule_id);
        if (!$schedule) return collect();

        $items = collect();
        foreach ($schedule->scheduleAccommodations->where('is_active', true)->sortBy('sort_order') as $acc) {
            $items->push((object)[
                'id' => 'acc_' . $acc->id,
                'name' => $acc->name,
                'description' => $acc->description,
                'price' => $acc->price,
            ]);
        }
        foreach ($schedule->transportClasses->where('pivot.is_active', true)->sortBy('pivot.sort_order') as $tc) {
            $items->push((object)[
                'id' => 'tc_' . $tc->id,
                'name' => $tc->name,
                'description' => $tc->pivot->description ?? $tc->description,
                'price' => $tc->pivot->additional_price,
            ]);
        }
        return $items;
    }

    public function calculateRebookingPriceDiff(): void
    {
        if (!$this->booking) return;

        $passengerCount = $this->booking->passengers()->count() ?: 1;

        $newTotal = 0.0;
        $newTotal += ($this->rebooking_dep_schedule_price ?? 0) * $passengerCount;
        $newTotal += ($this->rebooking_dep_accommodation_price ?? 0) * $passengerCount;

        if ($this->rebooking_is_round_trip) {
            $newTotal += ($this->rebooking_ret_schedule_price ?? 0) * $passengerCount;
            $newTotal += ($this->rebooking_ret_accommodation_price ?? 0) * $passengerCount;
        }

        if ($this->booking->has_vehicle) {
            $newTotal += $this->booking->vehicle_price;
        }

        $this->rebooking_new_total = $newTotal;
        $this->rebooking_price_diff = max(0, $newTotal - $this->booking->total_price);
        $rebookingFee = $this->booking->getRebookingFeeAmount();
        $this->rebooking_total_to_pay = $rebookingFee + $this->rebooking_price_diff;
    }


    public function submitRebookingProof(): void
    {
        $this->validate([
            'rebookingProof' => 'required|image|max:2048',
        ]);

        $this->isUploadingRebooking = true;

        $path = $this->rebookingProof->store('rebooking_proofs', 'public');

        $rebookingFee = $this->booking->getRebookingFeeAmount();

        $this->booking->transaction->update([
            'rebooking_fee' => $this->rebooking_total_to_pay,
            'rebooking_proof_of_payment' => $path,
        ]);

        $this->booking->update([
            'rebooking_status' => 'pending',
            'preferred_replacement_schedule_id' => $this->rebooking_dep_schedule_id,
            'preferred_replacement_date' => $this->rebooking_departure_date,
            'rebooking_departure_date' => $this->rebooking_departure_date,
            'rebooking_return_date' => $this->rebooking_is_round_trip ? $this->rebooking_return_date : null,
            'disruption_notes' => json_encode([
                'dep_schedule_id' => $this->rebooking_dep_schedule_id,
                'dep_accommodation_id' => $this->rebooking_dep_accommodation_id,
                'ret_schedule_id' => $this->rebooking_ret_schedule_id,
                'ret_accommodation_id' => $this->rebooking_ret_accommodation_id,
                'price_diff' => $this->rebooking_price_diff,
                'rebooking_fee' => $rebookingFee,
                'total_paid' => $this->rebooking_total_to_pay,
                'proof_path' => $path,
            ]),
        ]);

        try {
            Mail::to($this->booking->client_email)->send(new RebookingRequested($this->booking));
        } catch (Throwable $e) {
            Log::error('Failed sending rebooking requested email', [
                'booking_id' => $this->booking->id ?? null,
                'email' => $this->booking->client_email ?? null,
                'error' => $e->getMessage(),
            ]);
        }

        $this->isUploadingRebooking = false;
        $this->rebookingPaid = true;

        $this->feedback = "Rebooking fee & payment received and is now pending verification. Total paid: ₱" . number_format($this->rebooking_total_to_pay, 2) . ".";
    }

    private function getCancellationSessionKey(): string
    {
        return 'cancellation_window_expires_for_' . $this->transaction_number;
    }

    private function getCancellationExpiredKey(): string
    {
        return 'cancellation_expired_for_' . $this->transaction_number;
    }

    private function loadCancellationWindowFromSession(): void
    {
        if (! $this->booking) {
            return;
        }

        $remaining = $this->booking->created_at->addMinutes(5)->timestamp - now()->timestamp;
        if ($remaining <= 0) {
            $this->cancellationExpired = true;
            $this->cancelCountdown = 0;
        } else {
            $this->cancellationExpired = false;
            $this->cancelCountdown = $remaining;
        }
    }

    private function resetCancellationState(): void
    {
        $this->cancellationRequested = false;
        $this->cancellationWindowActive = false;
        $this->cancellationExpired = false;
        $this->cancelCountdown = 300;
        $this->refund_destination = null;
        $this->refund_method = 'GCash';
        $this->refund_bank_name = '';
        $this->refund_account_number = '';
        $this->refund_account_name = '';
        $this->showCancellationWarning = false;
        // NOTE: do NOT delete the cancellation_expired session key here.
        // It must survive page refreshes. Only startCancellationWindow() clears it
        // when the user explicitly begins a fresh cancellation attempt.
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
        $this->rebooking_step = 'departure_date';
        $this->rebooking_dep_schedule_id = null;
        $this->rebooking_dep_accommodation_id = null;
        $this->rebooking_dep_schedule_price = null;
        $this->rebooking_dep_accommodation_price = null;
        $this->rebooking_ret_schedule_id = null;
        $this->rebooking_ret_accommodation_id = null;
        $this->rebooking_ret_schedule_price = null;
        $this->rebooking_ret_accommodation_price = null;
        $this->rebooking_new_total = 0.0;
        $this->rebooking_price_diff = 0.0;
        $this->rebooking_total_to_pay = 0.0;
    }

    public function render()
    {
        return view('livewire.booking-lookup');
    }
}
