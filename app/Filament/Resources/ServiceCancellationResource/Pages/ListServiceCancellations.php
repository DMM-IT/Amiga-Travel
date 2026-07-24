<?php

namespace App\Filament\Resources\ServiceCancellationResource\Pages;

use App\Filament\Resources\ServiceCancellationResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListServiceCancellations extends ListRecords
{
    protected static string $resource = ServiceCancellationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make()
                ->label('New Service Cancellation'),
        ];
    }
}
