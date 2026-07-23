<?php

namespace App\Filament\Resources\GraciaEarningRuleResource\Pages;

use App\Filament\Resources\GraciaEarningRuleResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListGraciaEarningRules extends ListRecords
{
    protected static string $resource = GraciaEarningRuleResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
