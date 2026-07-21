<?php

namespace App\Filament\Resources\VehicleResource\Pages;

use App\Filament\Resources\VehicleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;
use Illuminate\Database\Eloquent\Builder;

class ListVehicles extends ListRecords
{
    protected static string $resource = VehicleResource::class;

    public string $vehicleType = 'ferry';

    public function setVehicleType($type)
    {
        $this->vehicleType = $type;
        $this->resetTable();
    }

    protected function getTableQuery(): Builder
    {
        return parent::getTableQuery()->where('type', $this->vehicleType);
    }

    protected function getHeaderActions(): array
    {
        return [
            Actions\Action::make('toggleType')
                ->view('filament.pages.actions.vehicle-type-toggle'),
            Actions\CreateAction::make(),
        ];
    }
}
