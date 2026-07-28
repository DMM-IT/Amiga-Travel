<?php

namespace App\Filament\Resources\ServiceCancellationResource\Pages;

use App\Filament\Resources\ServiceCancellationResource;
use App\Services\ServiceCancellationManager;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditServiceCancellation extends EditRecord
{
    protected static string $resource = ServiceCancellationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }

    protected function afterSave(): void
    {
        /** @var \App\Models\ServiceCancellation $cancellation */
        $cancellation = $this->getRecord();

        if (filled($cancellation->resume_date)) {
            app(ServiceCancellationManager::class)->declareResumeDate($cancellation, $cancellation->resume_date->format('Y-m-d'));
        }
    }
}
