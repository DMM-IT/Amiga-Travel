<?php

namespace App\Filament\Resources\ApkUserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingsRelationManager extends RelationManager
{
    protected static string $relationship = 'bookings';

    protected static ?string $recordTitleAttribute = 'transaction_number';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('transaction_number')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('transaction_number')
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                    ->label('Transaction No.')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('origin')
                    ->label('Route')
                    ->getStateUsing(fn ($record) => $record->origin . ' → ' . $record->destination)
                    ->searchable(['origin', 'destination']),
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Amount')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'confirmed' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\IconColumn::make('is_rebooked')
                    ->label('Rebooked')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Date Booked')
                    ->dateTime('M d, Y')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Read-only relation manager for APK users
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                //
            ])
            ->defaultSort('created_at', 'desc');
    }
}
