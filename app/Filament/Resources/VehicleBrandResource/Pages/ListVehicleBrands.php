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
            \Filament\Actions\Action::make('toggleView')
                ->view('filament.pages.actions.brand-category-toggle'),

            Actions\CreateAction::make(),
        ];
    }
}
