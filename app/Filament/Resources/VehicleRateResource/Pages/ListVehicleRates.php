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
            \Filament\Actions\Action::make('toggleView')
                ->view('filament.pages.actions.brand-category-toggle'),

            Actions\CreateAction::make(),
        ];
    }
}
