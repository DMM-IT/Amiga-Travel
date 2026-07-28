<?php

namespace App\Filament\Resources\ServiceCancellationResource\Pages;

use App\Filament\Resources\ServiceCancellationResource;
use App\Models\Booking;
use App\Models\Schedule;
use App\Models\ServiceCancellation;
use App\Models\ServiceCancellationReplacementSchedule;
use App\Services\ServiceCancellationManager;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;

use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Filament\Tables;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

use Filament\Actions\Action;
use Filament\Actions\EditAction;

class ViewServiceCancellation extends ViewRecord implements HasTable
{
    use InteractsWithTable;

    protected static string $resource = ServiceCancellationResource::class;

    protected function getHeaderActions(): array
    {
        /** @var ServiceCancellation $cancellation */
        $cancellation = $this->getRecord();

        return [
            EditAction::make(),

            Action::make('declare_resume_date')
                ->label('Declare Resume Date & Notify Customers')
                ->icon('heroicon-o-megaphone')
                ->color('success')
                ->visible(fn () => empty($cancellation->resume_date))
                ->form([
                    DatePicker::make('resume_date')
                        ->label('Official Service Resume Date')
                        ->helperText('Customers will be notified via email that operations are resuming and can pick replacement dates starting from this date.')
                        ->required()
                        ->minDate(now()),
                ])
                ->action(function (array $data) use ($cancellation) {
                    app(ServiceCancellationManager::class)->declareResumeDate($cancellation, $data['resume_date']);
                    Notification::make()
                        ->title('Service Resume Date Declared')
                        ->body('Notification emails have been queued for all affected customers.')
                        ->success()
                        ->send();

                    $this->redirect(ServiceCancellationResource::getUrl('view', ['record' => $cancellation]));
                }),

            Action::make('update_resume_date')
                ->label('Update Resume Date')
                ->icon('heroicon-o-calendar')
                ->color('secondary')
                ->visible(fn () => ! empty($cancellation->resume_date))
                ->form([
                    DatePicker::make('resume_date')
                        ->label('Service Resume Date')
                        ->default($cancellation->resume_date?->format('Y-m-d'))
                        ->required(),
                ])
                ->action(function (array $data) use ($cancellation) {
                    app(ServiceCancellationManager::class)->declareResumeDate($cancellation, $data['resume_date']);

                    Notification::make()
                        ->title('Resume Date Updated')
                        ->body("Service resume date set to " . ($cancellation->resume_date ? $cancellation->resume_date->format('M d, Y') : 'TBA') . ".")
                        ->success()
                        ->send();

                    $this->redirect(ServiceCancellationResource::getUrl('view', ['record' => $cancellation]));
                }),

            Action::make('add_replacement_schedule')
                ->label('Add Replacement Schedule Option')
                ->icon('heroicon-o-plus-circle')
                ->color('primary')
                ->form([
                    DatePicker::make('replacement_date')
                        ->label('Replacement Travel Date')
                        ->required()
                        ->default($cancellation->resume_date?->format('Y-m-d') ?? now()->toDateString()),

                    Select::make('schedule_id')
                        ->label('Eligible Replacement Schedule')
                        ->required()
                        ->options(function () use ($cancellation) {
                            $affectedSchedules = $cancellation->getAffectedSchedulesQuery()->get();
                            $routeIds = $affectedSchedules->pluck('ferry_route_id')->unique()->filter()->all();

                            return Schedule::query()
                                ->active()
                                ->whereIn('ferry_route_id', $routeIds)
                                ->get()
                                ->mapWithKeys(fn (Schedule $s) => [
                                    $s->id => "{$s->ferryRoute?->origin} → {$s->ferryRoute?->destination} | {$s->service_name} ({$s->formatted_departure})",
                                ]);
                        })
                        ->searchable(),
                ])
                ->action(function (array $data) use ($cancellation) {
                    ServiceCancellationReplacementSchedule::firstOrCreate([
                        'service_cancellation_id' => $cancellation->id,
                        'schedule_id' => $data['schedule_id'],
                        'replacement_date' => $data['replacement_date'],
                    ]);

                    Notification::make()
                        ->title('Replacement Schedule Added')
                        ->body('Eligible replacement schedule added for customer selection.')
                        ->success()
                        ->send();
                }),
        ];
    }

    public function table(Table $table): Table
    {
        /** @var ServiceCancellation $cancellation */
        $cancellation = $this->getRecord();

        return $table
            ->query(
                Booking::query()->where('service_cancellation_id', $cancellation->id)
            )
            ->columns([
                TextColumn::make('transaction_number')
                    ->label('Booking Ref')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),

                TextColumn::make('client_name')
                    ->label('Client Name')
                    ->searchable(),

                TextColumn::make('client_email')
                    ->label('Email')
                    ->searchable(),

                TextColumn::make('departure_date')
                    ->label('Original Date')
                    ->date(),

                TextColumn::make('disruption_status')
                    ->label('Disruption Status')
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'cancelled_by_operator_rescheduling_required' => 'Cancelled — Reschedule Required',
                        'reschedule_requested' => 'Reschedule Requested',
                        'rescheduled_approved' => 'Rescheduled — Approved',
                        'rescheduled_declined' => 'Rescheduled — Declined',
                        'contact_support_required' => 'Contact Support Required',
                        default => $state ? ucfirst(str_replace('_', ' ', $state)) : 'Cancelled',
                    })
                    ->badge()
                    ->colors([
                        'danger' => 'cancelled_by_operator_rescheduling_required',
                        'warning' => 'reschedule_requested',
                        'success' => 'rescheduled_approved',
                        'secondary' => ['rescheduled_declined', 'contact_support_required'],
                    ]),

                TextColumn::make('preferred_replacement_date')
                    ->label('Preferred Date')
                    ->date()
                    ->placeholder('Not chosen yet'),

                TextColumn::make('preferredReplacementSchedule.service_name')
                    ->label('Preferred Schedule')
                    ->placeholder('Not chosen yet'),

                TextColumn::make('disruption_notes')
                    ->label('Staff Note')
                    ->limit(30)
                    ->placeholder('—'),
            ])
            ->actions([
                Tables\Actions\Action::make('approve_reschedule')
                    ->label('Approve Reschedule')
                    ->color('success')
                    ->icon('heroicon-o-check-circle')
                    ->visible(fn (Booking $record) => in_array($record->disruption_status, ['reschedule_requested', 'cancelled_by_operator_rescheduling_required']))
                    ->form([
                        Textarea::make('staff_note')
                            ->label('Internal / Customer Staff Note')
                            ->placeholder('e.g., Approved replacement schedule per customer selection.')
                            ->rows(2),
                    ])
                    ->action(function (Booking $record, array $data) {
                        try {
                            app(ServiceCancellationManager::class)->processStaffApproval(
                                $record,
                                true,
                                $data['staff_note'] ?? null,
                                Auth::user()
                            );

                            Notification::make()
                                ->title('Reschedule Approved')
                                ->body("Booking #{$record->transaction_number} date updated and customer notified.")
                                ->success()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Approval Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),

                Tables\Actions\Action::make('decline_reschedule')
                    ->label('Decline Request')
                    ->color('danger')
                    ->icon('heroicon-o-x-circle')
                    ->visible(fn (Booking $record) => $record->disruption_status === 'reschedule_requested')
                    ->form([
                        Textarea::make('staff_note')
                            ->label('Reason for Declining')
                            ->required()
                            ->rows(2),
                    ])
                    ->action(function (Booking $record, array $data) {
                        try {
                            app(ServiceCancellationManager::class)->processStaffApproval(
                                $record,
                                false,
                                $data['staff_note'] ?? null,
                                Auth::user()
                            );

                            Notification::make()
                                ->title('Reschedule Declined')
                                ->body("Customer notified to choose another eligible date.")
                                ->warning()
                                ->send();
                        } catch (\Exception $e) {
                            Notification::make()
                                ->title('Error')
                                ->body($e->getMessage())
                                ->danger()
                                ->send();
                        }
                    }),
            ]);
    }
}
