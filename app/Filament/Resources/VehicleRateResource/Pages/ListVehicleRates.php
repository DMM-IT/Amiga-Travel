<?php

namespace App\Filament\Resources\VehicleRateResource\Pages;

use App\Filament\Resources\VehicleRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleRates extends ListRecords
{
    protected static string $resource = VehicleRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
