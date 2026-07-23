<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PromotionalTicketResource\Pages;
use App\Filament\Resources\PromotionalTicketResource\RelationManagers;
use App\Models\PromotionalTicket;
use App\Models\Schedule;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PromotionalTicketResource extends Resource
{
    protected static ?string $model = PromotionalTicket::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('schedule_id')
                    ->label('Schedule')
                    ->options(fn () => Schedule::with('ferryRoute')->get()->mapWithKeys(fn ($schedule) => [
                        $schedule->id => "{$schedule->ferryRoute?->mode} - {$schedule->ferryRoute?->operator} | {$schedule->ferryRoute?->origin} → {$schedule->ferryRoute?->destination} ({$schedule->formatted_departure})",
                    ]))
                    ->required()
                    ->searchable(),
                Forms\Components\TextInput::make('promo_price')
                    ->label('Promo Price (₱)')
                    ->numeric()
                    ->required()
                    ->step(0.01),
                Forms\Components\TextInput::make('quantity_available')
                    ->label('Quantity Available')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Forms\Components\TextInput::make('quantity_sold')
                    ->label('Quantity Sold')
                    ->numeric()
                    ->default(0)
                    ->minValue(0)
                    ->helperText('This is usually automatically updated when bookings are made'),
                Forms\Components\DateTimePicker::make('starts_at')
                    ->label('Starts At')
                    ->required()
                    ->native(false),
                Forms\Components\DateTimePicker::make('ends_at')
                    ->label('Ends At')
                    ->required()
                    ->native(false)
                    ->after('starts_at'),
                Forms\Components\Toggle::make('is_active')
                    ->label('Active')
                    ->default(true),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('schedule.id')
                    ->label('Schedule ID')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.mode')
                    ->label('Mode')
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.operator')
                    ->label('Operator')
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.origin')
                    ->label('Origin')
                    ->sortable(),
                Tables\Columns\TextColumn::make('schedule.ferryRoute.destination')
                    ->label('Destination')
                    ->sortable(),
                Tables\Columns\TextColumn::make('promo_price')
                    ->label('Promo Price')
                    ->money('PHP')
                    ->sortable(),
                Tables\Columns\TextColumn::make('quantity_available')
                    ->label('Total Available')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remaining_quantity')
                    ->label('Remaining')
                    ->numeric()
                    ->sortable()
                    ->formatStateUsing(fn (PromotionalTicket $record) => "{$record->remaining_quantity} of {$record->quantity_available}"),
                Tables\Columns\TextColumn::make('status_label')
                    ->label('Status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'Active' => 'success',
                        'Upcoming' => 'info',
                        'Inactive' => 'gray',
                        'Expired' => 'danger',
                        'Sold Out' => 'warning',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('starts_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\TextColumn::make('ends_at')
                    ->dateTime()
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListPromotionalTickets::route('/'),
            'create' => Pages\CreatePromotionalTicket::route('/create'),
            'edit' => Pages\EditPromotionalTicket::route('/{record}/edit'),
        ];
    }
}
