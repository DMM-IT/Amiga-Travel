<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ScheduleResource\Pages;
use App\Filament\Resources\ScheduleResource\RelationManagers\TransportClassesRelationManager;
use App\Models\FerryRoute;
use App\Models\Schedule;
use App\Models\User;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\TimePicker;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Table;

class ScheduleResource extends Resource
{
    protected static ?string $model = Schedule::class;

    protected static ?string $navigationIcon = 'heroicon-o-clock';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_schedules');
    }

    protected static ?string $navigationLabel = 'Schedules';

    protected static ?int $navigationSort = 3;

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
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('service_name')
                    ->label('Service name')
                    ->placeholder('e.g. Fast Ferry')
                    ->required()
                    ->maxLength(255),

                TextInput::make('vehicle_name')
                    ->label('Vehicle Name')
                    ->placeholder('e.g. MV Amiga, Flight 123')
                    ->nullable()
                    ->maxLength(255),

                TimePicker::make('departure_time')
                    ->label('Departure time')
                    ->seconds(false)
                    ->required(),

                TimePicker::make('arrival_time')
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

                CheckboxList::make('operating_days')
                    ->label('Operating days')
                    ->options([
                        1 => 'Monday',
                        2 => 'Tuesday',
                        3 => 'Wednesday',
                        4 => 'Thursday',
                        5 => 'Friday',
                        6 => 'Saturday',
                        7 => 'Sunday',
                    ])
                    ->columns(2)
                    ->required()
                    ->columnSpanFull(),

                TextInput::make('availability_label')
                    ->label('Availability note')
                    ->placeholder('e.g. Available, Limited availability')
                    ->maxLength(255),

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
                TextColumn::make('service_name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle_name')
                    ->label('Vehicle')
                    ->searchable()
                    ->sortable()
                    ->toggleable(),
                TextColumn::make('departure_time')
                    ->label('Departs')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('arrival_time')
                    ->label('Arrives')
                    ->time('H:i')
                    ->sortable(),
                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
                TextColumn::make('availability_label')
                    ->label('Availability')
                    ->placeholder('Available'),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('departure_time')
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
