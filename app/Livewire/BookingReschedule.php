<?php

namespace App\Livewire;

use App\Models\Booking;
use App\Models\ServiceCancellationReplacementSchedule;
use App\Services\ServiceCancellationManager;
use Livewire\Component;

class BookingReschedule extends Component
{
    public string $transaction_number = '';
    public ?Booking $booking = null;
    public ?int $selected_schedule_id = null;
    public ?string $selected_date = null;
    public ?string $feedback = null;
    public bool $submitted = false;
    public bool $supportRequested = false;

    public function mount(string $transaction_number): void
    {
        $this->transaction_number = ltrim(trim($transaction_number), '#');
        $this->loadBooking();
    }

    public function loadBooking(): void
    {
        $cleanNumber = ltrim(trim($this->transaction_number), '#');

        $this->booking = Booking::with([
            'serviceCancellation.replacementSchedules.schedule.ferryRoute',
            'preferredReplacementSchedule',
            'passengers',
        ])
        ->where(function ($query) use ($cleanNumber) {
            $query->where('transaction_number', $cleanNumber)
                  ->orWhere('transaction_number', '#' . $cleanNumber);
        })
        ->first();

        if ($this->booking && $this->booking->preferred_replacement_schedule_id) {
            $this->selected_schedule_id = $this->booking->preferred_replacement_schedule_id;
            $this->selected_date = $this->booking->preferred_replacement_date?->format('Y-m-d');
        }
    }

    public function selectOption(int $scheduleId, string $date): void
    {
        $this->selected_schedule_id = $scheduleId;
        $this->selected_date = $date;
        $this->feedback = null;
    }

    public function submitReschedule(): void
    {
        if (! $this->booking) {
            $this->feedback = 'Booking not found.';
            return;
        }

        if (! $this->selected_schedule_id || ! $this->selected_date) {
            $this->feedback = 'Please select a replacement date and schedule from the list below.';
            return;
        }

        try {
            app(ServiceCancellationManager::class)->submitCustomerReschedule(
                $this->booking,
                $this->selected_schedule_id,
                $this->selected_date
            );

            $this->loadBooking();
            $this->submitted = true;
            $this->feedback = 'Your preferred replacement travel date has been submitted successfully and is awaiting staff approval.';
        } catch (\Exception $e) {
            $this->feedback = $e->getMessage();
        }
    }

    public function requestSupport(): void
    {
        if (! $this->booking) {
            return;
        }

        $this->booking->update([
            'disruption_status' => 'contact_support_required',
        ]);

        $this->loadBooking();
        $this->supportRequested = true;
        $this->feedback = 'Our support team has been notified. We will reach out to your email shortly to assist with custom travel arrangements.';
    }

    public function render()
    {
        $eligibleReplacements = collect();

        if ($this->booking && $this->booking->serviceCancellation) {
            $cancellation = $this->booking->serviceCancellation;
            $resumeDate = $cancellation->resume_date?->format('Y-m-d');

            if ($resumeDate) {
                $eligibleReplacements = ServiceCancellationReplacementSchedule::with(['schedule.ferryRoute'])
                    ->where('service_cancellation_id', $cancellation->id)
                    ->whereDate('replacement_date', '>=', $resumeDate)
                    ->get()
                    ->filter(function ($item) {
                        // Match route origin & destination
                        return $item->schedule && $item->schedule->ferryRoute
                            && $item->schedule->ferryRoute->origin === $this->booking->origin
                            && $item->schedule->ferryRoute->destination === $this->booking->destination;
                    })
                    ->map(function ($item) {
                        // Convert Carbon date to string to avoid serialization issues in Livewire
                        $item->replacement_date_formatted = $item->replacement_date->format('Y-m-d');
                        return $item;
                    });
            }
        }

        return view('livewire.booking-reschedule', [
            'eligibleReplacements' => $eligibleReplacements,
        ]);
    }
}
