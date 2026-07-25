<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FerryRouteResource\Pages;
use App\Models\FerryRoute;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;

class FerryRouteResource extends Resource
{
    protected static ?string $model = FerryRoute::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';

    protected static ?string $navigationGroup = 'Travel';
    protected static ?int $navigationSort = 1;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('travel_routes');
    }

    public static function canCreate(): bool
    {
        return static::canAccess();
    }

    public static function canEdit($record): bool
    {
        return static::canAccess();
    }

    public static function canDelete($record): bool
    {
        return static::canAccess();
    }

    public static function canDeleteAny(): bool
    {
        return static::canAccess();
    }

    protected static ?string $navigationLabel = 'Travel Routes';

    protected static ?string $modelLabel = 'route';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('origin')
                    ->placeholder('e.g. Manila')
                    ->required()
                    ->maxLength(255),

                TextInput::make('destination')
                    ->placeholder('e.g. Boracay')
                    ->required()
                    ->maxLength(255),

                Select::make('mode')
                    ->label('Mode')
                    ->options([
                        'ferry' => 'Ferry',
                        'airline' => 'Airline',
                    ])
                    ->default('ferry')
                    ->reactive()
                    ->required()
                    ->afterStateUpdated(function (string $state, callable $set) {
                        $set('vehicle_id', null);
                        $set('operator', null);
                    }),

                Select::make('vehicle_id')
                    ->label('Vehicle')
                    ->options(fn (callable $get) => Vehicle::query()
                        ->when($get('mode'), fn ($query, $mode) => $query->where('type', $mode))
                        ->where('is_active', true)
                        ->orderBy('name')
                        ->get()
                        ->mapWithKeys(fn (Vehicle $vehicle) => [$vehicle->id => "{$vehicle->name} ({$vehicle->vehicle_id}) - {$vehicle->operator}"])
                        ->toArray())
                    ->reactive()
                    ->searchable()
                    ->afterStateHydrated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $set('operator', optional(Vehicle::find($state))->operator);

                            $schedules = $get('schedules') ?? [];
                            $vehicleName = optional(Vehicle::find($state))->vehicle_id;
                            foreach ($schedules as $index => $schedule) {
                                $schedules[$index]['vehicle_name'] = $vehicleName;
                            }
                            $set('schedules', $schedules);
                        }
                    })
                    ->afterStateUpdated(function ($state, callable $set, callable $get) {
                        if ($state) {
                            $set('operator', optional(Vehicle::find($state))->operator);

                            $schedules = $get('schedules') ?? [];
                            $vehicleName = optional(Vehicle::find($state))->vehicle_id;
                            foreach ($schedules as $index => $schedule) {
                                $schedules[$index]['vehicle_name'] = $vehicleName;
                            }
                            $set('schedules', $schedules);
                        } else {
                            $set('operator', null);

                            $schedules = $get('schedules') ?? [];
                            foreach ($schedules as $index => $schedule) {
                                $schedules[$index]['vehicle_name'] = null;
                            }
                            $set('schedules', $schedules);
                        }
                    })
                    ->hint('Select a vehicle from the ferry/airline list'),

                TextInput::make('operator')
                    ->label('Operator')
                    ->disabled()
                    ->reactive()
                    ->dehydrated(),

                Toggle::make('is_active')
                    ->label('Available for booking')
                    ->default(true),

                Section::make('Schedules for this Route')
                    ->description('Manage the schedules that belong to this route here. Changes are saved with the route.')
                    ->schema([
                        Repeater::make('schedules')
                            ->relationship('schedules')
                            ->label('')
                            ->schema(static::scheduleFormSchema())
                            ->defaultItems(0)
                            ->cloneable()
                            ->deletable()
                            ->collapsible()
                            ->collapsed()
                            ->itemLabel(fn (array $state): ?string => $state['service_name'] ?? 'New schedule')
                            ->createItemButtonLabel('Add schedule')
                            ->columnSpanFull(),
                    ])
                    ->columnSpanFull(),
            ])
            ->columns(2);
    }

    protected static function scheduleFormSchema(): array
    {
        return [
            TextInput::make('service_name')
                ->label('Name/Model')
                ->placeholder('e.g. Fast Ferry')
                ->required()
                ->maxLength(255),

            TextInput::make('vehicle_name')
                ->label('Vehicle')
                ->disabled()
                ->reactive()
                ->afterStateHydrated(function ($state, callable $set, callable $get) {
                    $vehicleId = $get('../../vehicle_id');

                    if ($vehicleId) {
                        $set('vehicle_name', optional(Vehicle::find($vehicleId))->vehicle_id);
                    }
                })
                ->nullable()
                ->maxLength(255),

            DateTimePicker::make('departure_time')
                ->label('Departure time')
                ->seconds(false)
                ->required(),

            DateTimePicker::make('arrival_time')
                ->label('Arrival time')
                ->seconds(false)
                ->required(),

            TextInput::make('duration_minutes')
                ->label('Duration (minutes)')
                ->helperText('Optional — calculated from times if left blank.')
                ->numeric()
                ->minValue(1),

            TextInput::make('price')
                ->label('Reseller price per passenger (₱)')
                ->numeric()
                ->prefix('₱')
                ->minValue(0)
                ->required(),

            TextInput::make('availability_label')
                ->label('Availability note')
                ->placeholder('e.g. Available, Limited availability')
                ->maxLength(255),

            TextInput::make('seat_rows')
                ->label('Seat rows (airline)')
                ->helperText('Number of seat rows for the seat map. Leave blank for default (30).')
                ->numeric()
                ->minValue(1)
                ->maxValue(60)
                ->visible(fn (callable $get): bool => $get('../../mode') === 'airline'),

            TagsInput::make('seat_columns')
                ->label('Seat columns (airline)')
                ->helperText('Column letters left to right, e.g. A, B, C, D, E, F. Leave blank for default.')
                ->placeholder('A')
                ->visible(fn (callable $get): bool => $get('../../mode') === 'airline'),

            Toggle::make('is_active')
                ->label('Visible to clients when booking')
                ->default(true),
        ];
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('origin')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('destination')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle.full_name')
                    ->label('Vehicle')
                    ->searchable(['name', 'vehicle_id'])
                    ->sortable(['name', 'vehicle_id']),
                TextColumn::make('operator')
                    ->label('Operator')
                    ->getStateUsing(fn (FerryRoute $record): ?string => $record->vehicle?->operator ?: $record->operator)
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mode')
                    ->label('Mode')
                    ->sortable(),
                TextColumn::make('schedules_count')
                    ->counts('schedules')
                    ->label('Schedules'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
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
            'index' => Pages\ListFerryRoutes::route('/'),
            'create' => Pages\CreateFerryRoute::route('/create'),
            'edit' => Pages\EditFerryRoute::route('/{record}/edit'),
        ];
    }
}
