<?php

namespace App\Filament\Resources\ServiceCancellationResource\Pages;

use App\Filament\Resources\ServiceCancellationResource;
use App\Models\ServiceCancellation;
use App\Services\ServiceCancellationManager;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class CreateServiceCancellation extends CreateRecord
{
    protected static string $resource = ServiceCancellationResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        /** @var ServiceCancellationManager $manager */
        $manager = app(ServiceCancellationManager::class);

        // Calculate preview details
        $preview = $manager->previewCancellation($data);

        $record = $manager->finalizeCancellation($data, Auth::user());

        Notification::make()
            ->title('Service Cancellation Finalized')
            ->body("Disruption code {$record->cancellation_code} created. {$preview['schedules_count']} schedules and {$preview['bookings_count']} bookings were updated and notified.")
            ->success()
            ->send();

        return $record;
    }

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('view', ['record' => $this->getRecord()]);
    }
}
