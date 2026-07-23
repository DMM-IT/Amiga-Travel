<?php

namespace App\Filament\Resources\PromotionalTicketResource\Pages;

use App\Filament\Resources\PromotionalTicketResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditPromotionalTicket extends EditRecord
{
    protected static string $resource = PromotionalTicketResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
