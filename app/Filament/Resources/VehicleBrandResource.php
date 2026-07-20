<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VehicleBrandResource\Pages;
use App\Filament\Resources\VehicleBrandResource\RelationManagers\VehicleModelsRelationManager;
use App\Models\User;
use App\Models\VehicleBrand;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Section;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class VehicleBrandResource extends Resource
{
    protected static ?string $model = VehicleBrand::class;

    protected static ?string $navigationIcon = 'heroicon-o-collection';

    public static function shouldRegisterNavigation(): bool
    {
        return false;
    }

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_vehicle_rates');
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Brand name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('sort_order')
                    ->label('Sort order')
                    ->numeric()
                    ->default(0)
                    ->helperText('Lower numbers appear first'),

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
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Brand')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('models_count')
                    ->counts('models')
                    ->label('Models')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('sort_order', 'asc')
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->filters([
                //
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
            VehicleModelsRelationManager::class,
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVehicleBrands::route('/brands'),
            'create' => Pages\CreateVehicleBrand::route('/brands/create'),
            'edit' => Pages\EditVehicleBrand::route('/brands/{record}/edit'),
        ];
    }
}
