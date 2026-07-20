<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Table;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $recordTitleAttribute = 'transaction_number';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('transaction_number')->label('Booking ref')->sortable()->searchable(),
                TextColumn::make('origin')->sortable()->searchable(),
                TextColumn::make('destination')->sortable()->searchable(),
                TextColumn::make('departure_date')->date()->sortable(),
                TextColumn::make('status')->sortable()->searchable(),
                TextColumn::make('total_price')->money('PHP')->sortable(),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
