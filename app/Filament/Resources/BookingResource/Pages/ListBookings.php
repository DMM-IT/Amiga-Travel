<?php

namespace App\Filament\Resources\BookingResource\Pages;

use App\Filament\Resources\BookingResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListBookings extends ListRecords
{
    protected static string $resource = BookingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
            Actions\ActionGroup::make([
                Actions\Action::make('download-pdf')
                    ->label('Download PDF')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->url(route('bookings.export.pdf'))
                    ->openUrlInNewTab(),
                Actions\Action::make('download-csv')
                    ->label('Download CSV')
                    ->icon('heroicon-m-arrow-down-tray')
                    ->url(route('bookings.export.csv'))
                    ->openUrlInNewTab(),
                Actions\Action::make('print-pdf')
                    ->label('Print (PDF)')
                    ->icon('heroicon-m-printer')
                    ->url(route('bookings.export.print'))
                    ->openUrlInNewTab(),
            ])
            ->label('Actions')
            ->icon('heroicon-m-ellipsis-vertical'),
        ];
    }
}
