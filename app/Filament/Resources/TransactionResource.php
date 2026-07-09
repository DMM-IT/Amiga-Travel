<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransactionResource\Pages;
use App\Models\Transaction;
use Filament\Infolists\Components\Section;
use Filament\Infolists\Components\TextEntry;
use Filament\Infolists\Components\ViewEntry;
use Filament\Infolists\Infolist;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class TransactionResource extends Resource
{
    protected static ?string $navigationIcon = 'heroicon-o-currency-dollar';

    protected static ?string $model = Transaction::class;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->with([
            'booking.passengers.discount',
            'booking.accommodations',
        ]);
    }

    public static function infolist(Infolist $infolist): Infolist
    {
        return $infolist
            ->schema([
                Section::make('Payment')
                    ->schema([
                        TextEntry::make('payment_status')
                            ->badge()
                            ->color(fn (string $state): string => match ($state) {
                                'paid' => 'success',
                                'pending' => 'warning',
                                'cancelled' => 'danger',
                                default => 'gray',
                            }),
                        TextEntry::make('created_at')
                            ->label('Submitted at')
                            ->dateTime(),
                        TextEntry::make('updated_at')
                            ->label('Last updated')
                            ->dateTime(),
                        ViewEntry::make('proof_of_payment')
                            ->label('Proof of payment')
                            ->view('filament.infolists.entries.proof-image')
                            ->visible(fn (?Transaction $record): bool => filled($record?->proof_of_payment))
                            ->columnSpanFull(),
                        TextEntry::make('proof_of_payment')
                            ->label('Proof of payment')
                            ->default('No proof uploaded yet.')
                            ->visible(fn (?Transaction $record): bool => blank($record?->proof_of_payment))
                            ->columnSpanFull(),
                    ])
                    ->columns(3),

                Section::make('Booking')
                    ->schema([
                        TextEntry::make('booking.transaction_number')
                            ->label('Transaction number'),
                        TextEntry::make('booking.status')
                            ->label('Booking status')
                            ->badge(),
                        TextEntry::make('booking.client_name')
                            ->label('Client name'),
                        TextEntry::make('booking.client_email')
                            ->label('Client email'),
                        TextEntry::make('booking.origin')
                            ->label('Origin'),
                        TextEntry::make('booking.destination')
                            ->label('Destination'),
                        TextEntry::make('booking.departure_date')
                            ->label('Departure date')
                            ->date(),
                        TextEntry::make('booking.return_date')
                            ->label('Return date')
                            ->date()
                            ->placeholder('One-way'),
                        TextEntry::make('booking.schedule_summary')
                            ->label('Ferry schedule')
                            ->placeholder('Not recorded'),
                        TextEntry::make('booking.schedule_price')
                            ->label('Schedule price (per passenger)')
                            ->money('PHP')
                            ->placeholder('—'),
                        TextEntry::make('booking.total_price')
                            ->label('Total amount')
                            ->money('PHP'),
                    ])
                    ->columns(3),

                Section::make('Passengers')
                    ->schema([
                        TextEntry::make('passengers_summary')
                            ->label('')
                            ->state(function (Transaction $record): array {
                                $passengers = $record->booking?->passengers ?? collect();

                                if ($passengers->isEmpty()) {
                                    return ['No passengers recorded.'];
                                }

                                return $passengers
                                    ->map(function ($passenger) {
                                        $label = ucfirst($passenger->type);

                                        if ($passenger->name) {
                                            $label .= " — {$passenger->name}";
                                        }

                                        $discount = $passenger->discount?->name ?? 'No discount';

                                        return "{$label} ({$discount})";
                                    })
                                    ->all();
                            })
                            ->listWithLineBreaks(),
                    ]),

                Section::make('Accommodations')
                    ->schema([
                        TextEntry::make('accommodations_summary')
                            ->label('')
                            ->state(function (Transaction $record): array {
                                $accommodations = $record->booking?->accommodations ?? collect();

                                if ($accommodations->isEmpty()) {
                                    return ['No accommodations selected.'];
                                }

                                return $accommodations
                                    ->map(fn ($accommodation) => "{$accommodation->name} — ₱".number_format((float) $accommodation->pivot->price, 2))
                                    ->all();
                            })
                            ->listWithLineBreaks(),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('booking.transaction_number')
                    ->label('Transaction')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('payment_status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'paid' => 'success',
                        'pending' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('booking.client_name')
                    ->label('Client name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('verify')
                    ->label('Verify')
                    ->action(fn (Transaction $record) => $record->update(['payment_status' => 'paid']))
                    ->requiresConfirmation()
                    ->color('success')
                    ->visible(fn (Transaction $record): bool => $record->payment_status !== 'paid'),
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
            'index' => Pages\ListTransactions::route('/'),
            'view' => Pages\ViewTransaction::route('/{record}'),
        ];
    }
}
