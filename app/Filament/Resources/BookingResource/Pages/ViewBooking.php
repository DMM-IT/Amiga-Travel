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
                                TextInput::make('seat_number')
                                    ->label('Seat')
                                    ->disabled(),
                                TextInput::make('seat_row')
                                    ->label('Row')
                                    ->disabled(),
                                TextInput::make('seat_section')
                                    ->label('Section')
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
            ]);
    }

    protected function mutateFormDataBeforeFill(array $data): array
    {
        return [
            ...$data,
            'transaction_payment_status' => $this->record->transaction?->payment_status,
            'proof_uploaded' => filled($this->record->transaction?->proof_of_payment) ? 'Yes' : 'No',
            'passengers' => $this->record->passengers->map(fn ($passenger) => [
                'name' => $passenger->name,
                'type' => $passenger->type,
                'discount' => $passenger->discount?->name ?: 'None',
                'seat_number' => $passenger->seat_number,
                'seat_row' => $passenger->seat_row,
                'seat_section' => $passenger->seat_section,
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
            Actions\Action::make('confirm')
                ->label('Confirm booking')
                ->action(function () {
                    $booking = $this->record;

                    $booking->update(['status' => 'confirmed']);

                    $receiptPath = storage_path('app/receipts/receipt-' . $booking->transaction_number . '.pdf');
                    if (! file_exists($receiptPath)) {
                        abort(404, 'Receipt not found. Generate the ticket PDF first.');
                    }

                    $ticketUrl = URL::temporarySignedRoute(
                        'ticket.download',
                        now()->addDays(7),
                        ['booking' => $booking->id]
                    );

                    Mail::to($booking->client_email)->send(new BookingConfirmation($booking, $ticketUrl, $receiptPath));

                    $this->notify('success', 'Booking confirmed and confirmation email sent.');
                })
                ->requiresConfirmation()
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'pending'),
        ];
    }
}
