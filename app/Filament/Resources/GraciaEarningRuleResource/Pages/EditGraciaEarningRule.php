<?php

namespace App\Filament\Resources\GraciaEarningRuleResource\Pages;

use App\Filament\Resources\GraciaEarningRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditGraciaEarningRule extends EditRecord
{
    protected static string $resource = GraciaEarningRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
