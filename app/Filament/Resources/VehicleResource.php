<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleResource\Pages;
use App\Models\User;
use App\Models\Vehicle;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VehicleResource extends Resource
{
    protected static ?string $model = Vehicle::class;

    protected static ?string $navigationIcon = 'heroicon-o-truck';

    protected static ?string $navigationGroup = 'Travel';
    protected static ?int $navigationSort = 3;

    protected static ?string $navigationLabel = 'Ferries & Airlines';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && ($user->is_admin || $user->is_staff);
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Select::make('type')
                    ->label('Vehicle Type')
                    ->options([
                        'ferry' => 'Ferry',
                        'airline' => 'Airline',
                    ])
                    ->required()
                    ->native(false),

                TextInput::make('name')
                    ->label('Vehicle Name')
                    ->placeholder('e.g. MV Superferry 16, Philippine Airlines PR123')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('vehicle_id')
                    ->label('Vehicle ID/Code')
                    ->placeholder('e.g. SF-16, PR123')
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),

                TextInput::make('operator')
                    ->label('Operating Company')
                    ->placeholder('e.g. Superferry, Philippine Airlines')
                    ->required()
                    ->maxLength(255),

                TextInput::make('capacity')
                    ->label('Passenger Capacity')
                    ->numeric()
                    ->minValue(1),

                Textarea::make('description')
                    ->label('Description')
                    ->placeholder('Additional details about this vehicle')
                    ->rows(3)
                    ->columnSpanFull(),

                Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('type')
                    ->label('Type')
                    ->formatStateUsing(fn (string $state): string => match ($state) {
                        'ferry' => '🚢 Ferry',
                        'airline' => '✈️ Airline',
                        default => ucfirst($state),
                    })
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ferry' => 'info',
                        'airline' => 'warning',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Vehicle Name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('vehicle_id')
                    ->label('ID/Code')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('operator')
                    ->label('Operator')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('capacity')
                    ->label('Capacity')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('type')
                    ->options([
                        'ferry' => 'Ferry',
                        'airline' => 'Airline',
                    ]),
                Tables\Filters\TernaryFilter::make('is_active')
                    ->label('Active'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicles::route('/'),
            'create' => Pages\CreateVehicle::route('/create'),
            'edit' => Pages\EditVehicle::route('/{record}/edit'),
        ];
    }
}
