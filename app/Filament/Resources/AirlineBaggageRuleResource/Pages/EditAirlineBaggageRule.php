<?php

namespace App\Filament\Resources\AirlineBaggageRuleResource\Pages;

use App\Filament\Resources\AirlineBaggageRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditAirlineBaggageRule extends EditRecord
{
    protected static string $resource = AirlineBaggageRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
