<?php

namespace App\Filament\Resources\TransportClassResource\Pages;

use App\Filament\Resources\TransportClassResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListTransportClasses extends ListRecords
{
    protected static string $resource = TransportClassResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
