<?php

namespace App\Filament\Resources\VehicleBrandResource\Pages;

use App\Filament\Resources\VehicleBrandResource;
use App\Filament\Resources\VehicleRateResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListVehicleBrands extends ListRecords
{
    protected static string $resource = VehicleBrandResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('category')
                ->label('Category')
                ->url(VehicleRateResource::getUrl('index'))
                ->color('secondary'),

            Actions\CreateAction::make(),
        ];
    }
}
