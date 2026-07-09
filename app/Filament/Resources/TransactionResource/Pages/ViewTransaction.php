<?php

namespace App\Filament\Resources\TransactionResource\Pages;

use App\Filament\Resources\TransactionResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewTransaction extends ViewRecord
{
    protected static string $resource = TransactionResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('verify')
                ->label('Verify payment')
                ->action(fn () => $this->record->update(['payment_status' => 'paid']))
                ->requiresConfirmation()
                ->color('success')
                ->visible(fn () => $this->record->payment_status !== 'paid'),
            Actions\DeleteAction::make(),
        ];
    }
}
