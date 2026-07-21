<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers\ScheduleAccommodationsRelationManager;
use App\Filament\Resources\ScheduleResource\RelationManagers\TransportClassesRelationManager;
use App\Models\FerryRoute;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Filters\SelectFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    protected static ?string $navigationGroup = 'Travel';
    protected static ?int $navigationSort = 3;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_schedules');
    }

    protected static ?string $navigationLabel = 'Schedules';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('ferry_route_id')
                    ->label('Route')
                    ->relationship('ferryRoute', 'origin')
                    ->getOptionLabelFromRecordUsing(fn (FerryRoute $record) => $record->label)
                    ->searchable(['origin', 'destination'])
                    ->preload()
                    ->reactive()
                    ->afterStateUpdated(function ($state, callable $set) {
                        if ($state) {
                            $route = \App\Models\FerryRoute::with('vehicle')->find($state);
                            if ($route?->vehicle) {
                                $set('service_name', $route->vehicle->name);
                                $set('vehicle_name', $route->vehicle->vehicle_id);
                            }
                        } else {
                            $set('service_name', null);
                            $set('vehicle_name', null);
                        }
                    })
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('service_name')
                    ->label('Name/Model')
                    ->placeholder('e.g. Fast Ferry')
                    ->required()
                    ->maxLength(255),

                TextInput::make('vehicle_name')
                    ->label('IMO/Tail No.')
                    ->placeholder('e.g. MV Amiga, Flight 123')
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
                    ->visible(fn (?Schedule $record) => $record?->ferryRoute?->mode === 'airline'),

                TagsInput::make('seat_columns')
                    ->label('Seat columns (airline)')
                    ->helperText('Column letters left to right, e.g. A, B, C, D, E, F. Leave blank for default.')
                    ->placeholder('A')
                    ->visible(fn (?Schedule $record) => $record?->ferryRoute?->mode === 'airline'),

                Toggle::make('is_active')
                    ->label('Visible to clients when booking')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('ferryRoute.origin')
                    ->label('Origin')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ferryRoute.destination')
                    ->label('Destination')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('ferryRoute.mode')
                    ->label('Mode')
                    ->sortable()
                    ->toggleable(),

                TextColumn::make('service_name')
                    ->label('Name/Model')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle_name')
                    ->label('IMO/Tail No.')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('departure_time')
                    ->label('Departs')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('arrival_time')
                    ->label('Arrives')
                    ->dateTime('M j, Y H:i')
                    ->sortable(),
                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('accommodation_label')
                    ->label('Accommodations')
                    ->wrap(),
                TextColumn::make('availability_label')
                    ->label('Availability')
                    ->placeholder('Available'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('departure_time')
            ->filters([
                SelectFilter::make('mode')
                    ->label('Travel mode')
                    ->options([
                        'ferry' => 'Ferry',
                        'airline' => 'Airline',
                    ])
                    ->query(function (Builder $query, array $data): void {
                        if (filled($data['value'] ?? null)) {
                            $query->whereHas('ferryRoute', function (Builder $query) use ($data): void {
                                $query->where('mode', $data['value']);
                            });
                        }
                    }),
                SelectFilter::make('ferry_route_id')
                    ->label('Operator')
                    ->relationship('ferryRoute', 'operator')
                    ->searchable(),
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
            ScheduleAccommodationsRelationManager::class,
            TransportClassesRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSchedules::route('/'),
            'create' => Pages\CreateSchedule::route('/create'),
            'edit' => Pages\EditSchedule::route('/{record}/edit'),
        ];
    }
}
