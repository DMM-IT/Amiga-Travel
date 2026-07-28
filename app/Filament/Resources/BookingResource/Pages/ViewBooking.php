<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Mail\BookingConfirmation;
use App\Models\Booking;
use Filament\Actions;
use Filament\Forms;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\HtmlString;
use Illuminate\Support\Facades\Log;
use Throwable;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Booking details')
                    ->schema([
                        TextInput::make('transaction_number')
                            ->label('Transaction number'),
                        TextInput::make('client_name')
                            ->label('Client name'),
                        TextInput::make('client_email')
                            ->label('Client email'),
                        TextInput::make('origin')
                            ->label('Origin'),
                        TextInput::make('destination')
                            ->label('Destination'),
                        TextInput::make('status')
                            ->label('Booking status'),
                        TextInput::make('schedule_service')
                            ->label('Schedule'),
                        TextInput::make('schedule_departure_time')
                            ->label('Departure time'),
                        TextInput::make('schedule_arrival_time')
                            ->label('Arrival time'),
                        DatePicker::make('departure_date')
                            ->label('Departure date'),
                        DatePicker::make('return_date')
                            ->label('Return date'),
                        TextInput::make('total_price')
                            ->label('Total price')
                            ->prefix('₱'),
                        TextInput::make('transaction_payment_status')
                            ->label('Payment status'),
                        TextInput::make('proof_uploaded')
                            ->label('Proof uploaded'),
                        Placeholder::make('proof_image')
                            ->label('Proof of payment')
                            ->content(fn (): HtmlString => $this->record->transaction?->proof_url
                                ? new HtmlString('<img src="' . e($this->record->transaction->proof_url) . '" class="rounded-lg border border-gray-700 max-w-full h-auto" alt="Proof of payment" />')
                                : new HtmlString('No proof uploaded')),
                    ])
                    ->columns(2),
                Section::make('Passenger details')
                    ->schema([
                        Repeater::make('passengers')
                            ->label('Passengers')
                            ->disableLabel()
                            ->schema([
                                TextInput::make('name')
                                    ->label('Name')
                                    ->disabled(),
                                TextInput::make('type')
                                    ->label('Type')
                                    ->disabled(),
                                TextInput::make('discount')
                                    ->label('Discount')
                                    ->disabled(),
                            ])
                            ->columns(2)
                            ->visible(fn (): bool => $this->record->passengers->isNotEmpty()),
                    ]),
                Section::make('Vehicle details')
                    ->schema([
                        Toggle::make('has_vehicle')
                            ->label('Has vehicle'),
                        TextInput::make('vehicle_type')
                            ->label('Vehicle type')
                            ->nullable(),
                        TextInput::make('vehicle_plate_number')
                            ->label('Plate number')
                            ->nullable(),
                        TextInput::make('vehicle_price')
                            ->label('Vehicle price')
                            ->prefix('₱')
                            ->nullable(),
                    ])
                    ->columns(2)
                    ->visible(fn (): bool => $this->record->has_vehicle && $this->record->schedule?->ferryRoute?->mode !== 'airline'),

                Section::make('Disruption & Rebooking Details')
                    ->schema([
                        TextInput::make('disruption_status_label')
                            ->label('Disruption Status'),
                        TextInput::make('rebooking_status_label')
                            ->label('Rebooking Status'),
                        DatePicker::make('preferred_replacement_date')
                            ->label('Preferred Replacement Date'),
                        TextInput::make('preferred_replacement_schedule_label')
                            ->label('Preferred Replacement Schedule'),
                        Textarea::make('disruption_notes')
                            ->label('Staff Approval Notes')
                            ->columnSpanFull(),
                    ])
                    ->columns(2)
                    ->visible(fn (): bool => filled($this->record->service_cancellation_id) || filled($this->record->rebooking_status) || filled($this->record->disruption_status)),
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        $prefSchedule = $this->record->preferredReplacementSchedule;

        return [
            ...$data,
            'transaction_payment_status' => $this->record->transaction?->payment_status,
            'proof_uploaded' => filled($this->record->transaction?->proof_of_payment) ? 'Yes' : 'No',
            'disruption_status_label' => match ($this->record->disruption_status) {
                'cancelled_by_operator_rescheduling_required' => 'Cancelled by Operator — Reschedule Required',
                'reschedule_requested' => 'Customer Reschedule Requested',
                'rescheduled_approved' => 'Rescheduled & Approved',
                'rescheduled_declined' => 'Rescheduled — Declined',
                'contact_support_required' => 'Contact Support Required',
                default => $this->record->disruption_status ? ucfirst(str_replace('_', ' ', $this->record->disruption_status)) : '—',
            },
            'rebooking_status_label' => match ($this->record->rebooking_status) {
                'rebooking_required' => 'Rebooking Required',
                'reschedule_requested' => 'Reschedule Requested',
                'verified' => 'Rebooked',
                'pending' => 'Pending',
                default => $this->record->rebooking_status ? ucfirst(str_replace('_', ' ', $this->record->rebooking_status)) : '—',
            },
            'preferred_replacement_schedule_label' => $prefSchedule
                ? "{$prefSchedule->service_name} ({$prefSchedule->formatted_departure} → {$prefSchedule->formatted_arrival})"
                : '—',
            'passengers' => $this->record->passengers->map(fn ($passenger) => [
                'name' => $passenger->name,
                'type' => $passenger->type,
                'discount' => $passenger->discount?->name ?: 'None',
            ])->toArray(),
            'proof_url' => $this->record->transaction?->proof_url ? $this->record->transaction->proof_url : 'No proof uploaded',
        ];
    }

    protected function getAllRelationManagers(): array
    {
        return [];
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('approveReschedule')
                ->label('Approve Reschedule Request')
                ->icon('heroicon-o-check-circle')
                ->color('success')
                ->visible(fn (): bool => in_array($this->record->disruption_status, ['reschedule_requested', 'cancelled_by_operator_rescheduling_required']) && filled($this->record->preferred_replacement_schedule_id))
                ->form([
                    Textarea::make('staff_note')
                        ->label('Internal / Customer Staff Note')
                        ->placeholder('e.g., Approved replacement schedule per customer selection.')
                        ->rows(2),
                ])
                ->action(function (array $data) {
                    app(\App\Services\ServiceCancellationManager::class)->processStaffApproval(
                        $this->record,
                        true,
                        $data['staff_note'] ?? null,
                        auth()->user()
                    );

                    \Filament\Notifications\Notification::make()
                        ->title('Reschedule Approved')
                        ->body("Booking #{$this->record->transaction_number} date updated to " . $this->record->departure_date->format('M d, Y') . " and customer notified.")
                        ->success()
                        ->send();

                    $this->redirect(BookingResource::getUrl('view', ['record' => $this->record]));
                }),

            Actions\Action::make('confirm')
                ->label('Confirm booking')
                ->action(function () {
                    $booking = $this->record;

                    $booking->update([
                        'status' => 'confirmed',
                        'verified_by_user_id' => \Auth::id(),
                        'verified_at' => now(),
                    ]);

                    if ($booking->transaction && $booking->transaction->payment_status !== 'paid') {
                        $booking->transaction->update([
                            'payment_status' => 'paid',
                            'verified_by_user_id' => \Auth::id(),
                            'verified_at' => now(),
                        ]);
                    }

                    $receiptPath = storage_path('app/receipts/receipt-' . $booking->transaction_number . '.pdf');
                    if (! file_exists($receiptPath)) {
                        abort(404, 'Receipt not found. Generate the ticket PDF first.');
                    }

                    $ticketUrl = URL::temporarySignedRoute(
                        'ticket.download',
                        now()->addDays(7),
                        ['booking' => $booking->id]
                    );

                    try {
                        Mail::to($booking->client_email)->send(new BookingConfirmation($booking, $ticketUrl, $receiptPath));
                        $this->notify('success', 'Booking confirmed and confirmation email sent.');
                    } catch (Throwable $e) {
                        Log::error('Failed sending booking confirmation email', [
                            'booking_id' => $booking->id ?? null,
                            'email' => $booking->client_email ?? null,
                            'error' => $e->getMessage(),
                        ]);
                        $this->notify('warning', 'Booking confirmed but confirmation email failed to send.');
                    }
                })
                ->requiresConfirmation()
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'pending'),
        ];
    }
}
