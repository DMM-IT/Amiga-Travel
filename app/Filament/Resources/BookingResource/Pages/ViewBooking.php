<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use App\Mail\BookingConfirmation;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;

class ViewBooking extends ViewRecord
{
    protected static string $resource = BookingResource::class;

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
                ->visible(fn () => $this->record->status !== 'confirmed'),
        ];
    }
}
