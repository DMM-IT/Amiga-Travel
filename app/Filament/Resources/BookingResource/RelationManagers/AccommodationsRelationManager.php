<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\Accommodation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class AccommodationsRelationManager extends RelationManager
{
    protected static string $relationship = 'accommodations';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('accommodation_id')
                    ->label('Accommodation')
                    ->options(Accommodation::where('is_active', true)->pluck('name', 'id'))
                    ->required(),
                Forms\Components\TextInput::make('price')
                    ->label('Price (₱)')
                    ->numeric()
                    ->prefix('₱')
                    ->required(),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name'),
                Tables\Columns\TextColumn::make('price')
                    ->money('PHP'),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\AttachAction::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DetachAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DetachBulkAction::make(),
                ]),
            ]);
    }
}
