<?php

namespace App\Filament\Resources\ApkUserResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class GraciaPointLedgersRelationManager extends RelationManager
{
    protected static string $relationship = 'graciaPointLedgers';
    protected static ?string $title = 'Gracia Points Ledger';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('points')
                    ->required()
                    ->maxLength(255),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('points')
            ->columns([
                Tables\Columns\TextColumn::make('points')
                    ->color(fn ($state) => $state >= 0 ? 'success' : 'danger')
                    ->sortable(),
                Tables\Columns\TextColumn::make('entry_type')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'earned' => 'success',
                        'reversed' => 'danger',
                        'admin_adjustment' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('qualifying_spend_centavos')
                    ->label('Qualifying Spend (PHP)')
                    ->formatStateUsing(fn ($state) => $state != 0 ? '₱' . number_format($state / 100, 2) : '-')
                    ->sortable(),
                Tables\Columns\TextColumn::make('reason'),
                Tables\Columns\TextColumn::make('admin.name')
                    ->label('Admin'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                // Ledger is append only and created automatically or via manual adjustments
            ])
            ->actions([
                // No edits or deletes
            ])
            ->bulkActions([
                // No bulk deletes
            ])
            ->defaultSort('created_at', 'desc');
    }
}
