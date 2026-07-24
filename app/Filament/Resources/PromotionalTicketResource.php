<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionalTicketResource\Pages;
use App\Filament\Resources\PromotionalTicketResource\RelationManagers;
use App\Models\PromotionalTicket;
use App\Models\Schedule;
use App\Models\FerryRoute;
use App\Models\Vehicle;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Forms\Get;
use Filament\Infolists;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionalTicketResource extends Resource
{
    protected static ?string $model = PromotionalTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('mode')
                    ->label('Mode')
                    ->options(function (): array {
                        return FerryRoute::query()
                            ->active()
                            ->join('schedules', 'ferry_routes.id', '=', 'schedules.ferry_route_id')
                            ->distinct()
                            ->pluck('ferry_routes.mode', 'ferry_routes.mode')
                            ->map(fn(string $mode) => ucfirst($mode))
                            ->toArray();
                    })
                    ->live()
                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                        $set('operator', null);
                        $set('vehicle_id', null);
                        $set('schedule_id', null);
                    })
                    ->required()
                    ->placeholder('Select mode first'),
                Forms\Components\Select::make('operator')
                    ->label('Operator')
                    ->options(fn(Get $get): array => $get('mode')
                        ? FerryRoute::query()
                            ->active()
                            ->where('mode', $get('mode'))
                            ->join('schedules', 'ferry_routes.id', '=', 'schedules.ferry_route_id')
                            ->whereNotNull('operator')
                            ->distinct()
                            ->pluck('ferry_routes.operator', 'ferry_routes.operator')
                            ->toArray()
                        : []
                    )
                    ->live()
                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                        $set('vehicle_id', null);
                        $set('schedule_id', null);
                    })
                    ->required()
                    ->placeholder(fn(Get $get): string => $get('mode') ? 'Select operator first' : 'Select mode first'),
                Forms\Components\Select::make('vehicle_id')
                    ->label('Vehicle Name')
                    ->options(fn(Get $get): array => $get('operator') && $get('mode')
                        ? Vehicle::query()
                            ->where('type', $get('mode'))
                            ->where('operator', $get('operator'))
                            ->active()
                            ->whereHas('ferryRoutes', function (Builder $q) use ($get): void {
                                $q->where('mode', $get('mode'))
                                    ->where('operator', $get('operator'))
                                    ->active()
                                    ->has('schedules');
                            })
                            ->get()
                            ->mapWithKeys(fn(Vehicle $v): array => [
                                $v->id => $v->name . ($v->vehicle_id ? " — {$v->vehicle_id}" : '')
                            ])
                            ->toArray()
                        : []
                    )
                    ->live()
                    ->afterStateUpdated(function (mixed $state, callable $set): void {
                        $set('schedule_id', null);
                    })
                    ->required()
                    ->searchable()
                    ->placeholder(fn(Get $get): string => $get('operator') ? 'Select vehicle first' : 'Select operator first'),
                Forms\Components\Select::make('schedule_id')
                    ->label('Schedule')
                    ->options(function (Get $get): array {
                        if (! $get('vehicle_id')) {
                            return [];
                        }

                        return Schedule::query()
                            ->with('ferryRoute')
                            ->active()
                            ->whereHas('ferryRoute', function (Builder $q) use ($get): void {
                                $q->where('vehicle_id', $get('vehicle_id'))
                                    ->where('operator', $get('operator'))
                                    ->where('mode', $get('mode'));
                            })
                            ->get()
                            ->mapWithKeys(function (Schedule $schedule): array {
                                /** @var \Carbon\Carbon|null $departureTime */
                                $departureTime = $schedule->departure_time;
                                /** @var \Carbon\Carbon|null $arrivalTime */
                                $arrivalTime = $schedule->arrival_time;
                                
                                $departure = $departureTime?->format('M j, Y H:i');
                                $arrival = $arrivalTime?->format('H:i');
                                return [
                                    $schedule->id => "{$schedule->ferryRoute?->origin} → {$schedule->ferryRoute?->destination} • {$departure}–{$arrival} • ₱{$schedule->price}"
                                ];
                            })
                            ->toArray();
                    })
                    ->required()
                    ->searchable()
                    ->placeholder(fn(Get $get): string => $get('vehicle_id') ? 'Select schedule first' : 'Select vehicle first'),
                Forms\Components\TextInput::make('promo_price')
                    ->label('Promo Price (₱)')
                    ->numeric()
                    ->required()
                    ->step(0.01),
                Forms\Components\TextInput::make('quantity_available')
                    ->label('Quantity Available')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Forms\Components\TextInput::make('quantity_sold')
                    ->label('Quantity Sold')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->helperText('This is usually automatically updated when bookings are made'),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->label('Starts At')
                    ->required()
                    ->native(false),
                Forms\Components\DateTimePicker::make('ends_at')
                    ->label('Ends At')
                    ->required()
                    ->native(false)
                    ->after('starts_at'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    protected static function mutateFormDataBeforeFill(array $data, ?PromotionalTicket $record): array
    {
        if ($record && $record->schedule) {
            $data['mode'] = $record->schedule->ferryRoute?->mode;
            $data['operator'] = $record->schedule->ferryRoute?->operator;
            $data['vehicle_id'] = $record->schedule->ferryRoute?->vehicle_id;
        }

        return $data;
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Infolists\Components\TextEntry::make('schedule.ferryRoute.mode')
                    ->label('Mode')
                    ->formatStateUsing(fn(mixed $state): string => ucfirst((string) $state)),
                Infolists\Components\TextEntry::make('schedule.ferryRoute.operator')
                    ->label('Operator'),
                Infolists\Components\TextEntry::make('schedule.ferryRoute.vehicle.name')
                    ->label('Vehicle Name'),
                Infolists\Components\TextEntry::make('schedule.ferryRoute.vehicle.vehicle_id')
                    ->label('Vehicle ID'),
                Infolists\Components\TextEntry::make('schedule.ferryRoute.origin')
                    ->label('Origin'),
                Infolists\Components\TextEntry::make('schedule.ferryRoute.destination')
                    ->label('Destination'),
                Infolists\Components\TextEntry::make('schedule.departure_time')
                    ->label('Departure Date & Time')
                    ->dateTime('M j, Y H:i'),
                Infolists\Components\TextEntry::make('schedule.arrival_time')
                    ->label('Arrival Date & Time')
                    ->dateTime('M j, Y H:i'),
                Infolists\Components\TextEntry::make('schedule.price')
                    ->label('Regular Price')
                    ->money('PHP'),
                Infolists\Components\TextEntry::make('promo_price')
                    ->label('Promo Price')
                    ->money('PHP'),
                Infolists\Components\TextEntry::make('remaining_quantity')
                    ->label('Remaining Quantity')
                    ->formatStateUsing(fn(PromotionalTicket $record): string => "{$record->remaining_quantity} / {$record->quantity_available}"),
                Infolists\Components\TextEntry::make('quantity_sold')
                    ->label('Quantity Sold'),
                Infolists\Components\TextEntry::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn(string $state): string => match ($state) {
                        'Active' => 'success',
                        'Upcoming' => 'info',
                        'Inactive' => 'gray',
                        'Expired' => 'danger',
                        'Sold Out' => 'warning',
                        default => 'gray',
                    }),
                Infolists\Components\TextEntry::make('starts_at')
                    ->label('Starts At')
                    ->dateTime(),
                Infolists\Components\TextEntry::make('ends_at')
                    ->label('Ends At')
                    ->dateTime(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.ferryRoute.mode')
                    ->label('Mode')
                    ->formatStateUsing(fn(mixed $state): string => ucfirst((string) $state))
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.operator')
                    ->label('Operator')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.vehicle.name')
                    ->label('Vehicle Name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.vehicle.vehicle_id')
                    ->label('Vehicle ID')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('route')
                    ->label('Route')
                    ->getStateUsing(fn(PromotionalTicket $record): string => "{$record->schedule?->ferryRoute?->origin} → {$record->schedule?->ferryRoute?->destination}")
                    ->searchable(query: function (Builder $query, string $search): Builder {
                        return $query->whereHas('schedule.ferryRoute', function (Builder $routeQuery) use ($search) {
                            $routeQuery->where('origin', 'like', "%{$search}%")
                                ->orWhere('destination', 'like', "%{$search}%");
                        });
                    }),
                Tables\Columns\TextColumn::make('schedule.departure_time')
                    ->label('Departure Date & Time')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promo_price')
                    ->label('Promo Price')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_available')
                    ->label('Quantity Available')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_sold')
                    ->label('Quantity Sold')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_quantity')
                    ->label('Remaining')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Upcoming' => 'info',
                        'Inactive' => 'gray',
                        'Expired' => 'danger',
                        'Sold Out' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('mode')
                    ->label('Mode')
                    ->options(
                        FerryRoute::query()
                            ->active()
                            ->distinct()
                            ->orderBy('mode')
                            ->pluck('mode', 'mode')
                            ->map(fn (string $mode): string => ucfirst($mode))
                            ->toArray()
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, string $mode): Builder => $query->whereHas(
                                'schedule.ferryRoute',
                                fn (Builder $routeQuery): Builder => $routeQuery->where('mode', $mode)
                            )
                        );
                    }),
                Tables\Filters\SelectFilter::make('operator')
                    ->label('Operator')
                    ->options(
                        FerryRoute::query()
                            ->active()
                            ->whereNotNull('operator')
                            ->where('operator', '!=', '')
                            ->distinct()
                            ->orderBy('operator')
                            ->pluck('operator', 'operator')
                            ->toArray()
                    )
                    ->query(function (Builder $query, array $data): Builder {
                        return $query->when(
                            $data['value'] ?? null,
                            fn (Builder $query, string $operator): Builder => $query->whereHas(
                                'schedule.ferryRoute',
                                fn (Builder $routeQuery): Builder => $routeQuery->where('operator', $operator)
                            )
                        );
                    }),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active Status'),
                Tables\Filters\Filter::make('upcoming')
                    ->label('Upcoming')
                    ->query(fn(Builder $q): Builder => $q->where('starts_at', '>', now())),
                Tables\Filters\Filter::make('sold_out')
                    ->label('Sold Out')
                    ->query(fn(Builder $q): Builder => $q->whereColumn('quantity_sold', '>=', 'quantity_available')),
                Tables\Filters\Filter::make('expired')
                    ->label('Expired')
                    ->query(fn(Builder $q): Builder => $q->where('ends_at', '<', now())),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->with(['schedule.ferryRoute.vehicle']);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPromotionalTickets::route('/'),
            'create' => Pages\CreatePromotionalTicket::route('/create'),
            'view' => Pages\ViewPromotionalTicket::route('/{record}'),
            'edit' => Pages\EditPromotionalTicket::route('/{record}/edit'),
        ];
    }
}
