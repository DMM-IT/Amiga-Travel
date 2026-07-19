<?php

namespace App\Filament\Resources;

use App\Filament\Resources\FerryRouteResource\Pages;
use App\Models\FerryRoute;
use App\Models\User;
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

        return $user instanceof User && $user->hasAdminPermission('manage_routes');
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
                    ->required(),

                Select::make('vehicle_id')
                    ->label('Vehicle')
                    ->relationship('vehicle', 'name')
                    ->getOptionLabelFromRecordUsing(fn (\App\Models\Vehicle $record) => "{$record->name} ({$record->vehicle_id}) - {$record->operator}")
                    ->preload()
                    ->searchable()
                    ->hint('Select a vehicle or leave empty to add operator manually'),

                TextInput::make('operator')
                    ->label('Operator (Fallback)')
                    ->placeholder('e.g. 2GO, Starlight, Cebu Pacific')
                    ->maxLength(255)
                    ->helperText('Used if no vehicle is selected'),

                Toggle::make('is_active')
                    ->label('Available for booking')
                    ->default(true),
            ])
            ->columns(2);
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
                    ->searchable()
                    ->sortable(),
                TextColumn::make('operator')
                    ->label('Operator')
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
