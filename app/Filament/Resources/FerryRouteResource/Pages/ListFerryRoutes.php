<?php

namespace App\Filament\Resources\FerryRouteResource\Pages;

use App\Filament\Resources\FerryRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListFerryRoutes extends ListRecords
{
    protected static string $resource = FerryRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
