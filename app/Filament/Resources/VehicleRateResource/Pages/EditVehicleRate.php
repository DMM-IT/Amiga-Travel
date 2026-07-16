<?php

namespace App\Filament\Resources\VehicleRateResource\Pages;

use App\Filament\Resources\VehicleRateResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditVehicleRate extends EditRecord
{
    protected static string $resource = VehicleRateResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
