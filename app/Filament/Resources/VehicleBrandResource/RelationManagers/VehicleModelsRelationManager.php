<?php

namespace App\Filament\Resources\VehicleBrandResource\RelationManagers;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class VehicleModelsRelationManager extends RelationManager
{
    protected static string $relationship = 'models';

    protected static ?string $recordTitleAttribute = 'name';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->label('Model name')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Price (₱)')
                    ->numeric()
                    ->prefix('₱')
                    ->minValue(0)
                    ->required(),

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

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('sort_order')
                    ->label('Order')
                    ->sortable(),
                TextColumn::make('name')
                    ->label('Model')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->label('Price')
                    ->money('PHP')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
            ])
            ->defaultSort('sort_order', 'asc')
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
