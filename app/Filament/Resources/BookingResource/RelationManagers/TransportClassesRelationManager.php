<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\TransportClass;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TransportClassesRelationManager extends RelationManager
{
    protected static string $relationship = 'transportClasses';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('transport_class_id')
                    ->label('Transport Class')
                    ->options(TransportClass::where('is_active', true)->pluck('name', 'id'))
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
