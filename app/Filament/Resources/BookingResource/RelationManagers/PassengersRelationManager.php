<?php

namespace App\Filament\Resources\BookingResource\RelationManagers;

use App\Models\Passenger;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PassengersRelationManager extends RelationManager
{
    protected static string $relationship = 'passengers';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('Passenger Name')
                    ->required(),
                Forms\Components\Select::make('type')
                    ->options([
                        'adult' => 'Adult',
                        'child' => 'Child',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('seat_number')
                    ->label('Seat Number')
                    ->nullable(),
                Forms\Components\TextInput::make('seat_row')
                    ->label('Seat Row')
                    ->nullable(),
                Forms\Components\TextInput::make('seat_section')
                    ->label('Seat Section')
                    ->nullable(),
            ])
            ->columns(2);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('type'),
                Tables\Columns\TextColumn::make('seat_number')
                    ->label('Seat')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('seat_row')
                    ->label('Row')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('seat_section')
                    ->label('Section')
                    ->toggleable(),
            ])
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
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }
}
