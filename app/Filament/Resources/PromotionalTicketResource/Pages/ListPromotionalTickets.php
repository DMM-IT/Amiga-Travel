<?php

namespace App\Filament\Resources\PromotionalTicketResource\Pages;

use App\Filament\Resources\PromotionalTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPromotionalTickets extends ListRecords
{
    protected static string $resource = PromotionalTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
