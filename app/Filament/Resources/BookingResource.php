<?php

namespace App\Filament\Resources;

use App\Filament\Resources\BookingResource\Pages;
use App\Filament\Resources\BookingResource\RelationManagers\TransportClassesRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\AccommodationsRelationManager;
use App\Filament\Resources\BookingResource\RelationManagers\PassengersRelationManager;
use App\Mail\RebookingVerification;
use App\Models\Booking;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BookingResource extends Resource
{
    protected static ?string $model = Booking::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Bookings';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Bookings';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('bookings');
    }

    public static function canCreate(): bool
    {
        return static::canAccess();
    }

    public static function canEdit($record): bool
    {
        return static::canAccess();
    }

    public static function canDelete($record): bool
    {
        return static::canAccess();
    }

    public static function canDeleteAny(): bool
    {
        return static::canAccess();
    }

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Toggle::make('has_vehicle')
                    ->label('Has Vehicle')
                    ->default(false),
                Forms\Components\TextInput::make('vehicle_type')
                    ->label('Vehicle Type')
                    ->placeholder('e.g. Car, Motorcycle')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('vehicle_plate_number')
                    ->label('Plate Number')
                    ->placeholder('e.g. ABC123')
                    ->maxLength(255)
                    ->nullable(),
                Forms\Components\TextInput::make('vehicle_price')
                    ->label('Vehicle Price (₱)')
                    ->numeric()
                    ->prefix('₱')
                    ->nullable()
                    ->minValue(0),
            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->modifyQueryUsing(fn (Builder $query) => $query)
            ->defaultSort('created_at', 'desc')
            ->columns([
                Tables\Columns\TextColumn::make('transaction_number')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('client_name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('origin')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('destination')
                    ->toggleable(),
                Tables\Columns\TextColumn::make('schedule_service')
                    ->label('Schedule')
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('departure_date')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rebooking_departure_date')
                    ->label('Rebook Departure')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('rebooking_return_date')
                    ->label('Rebook Return')
                    ->date()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->placeholder('—'),
                Tables\Columns\IconColumn::make('has_vehicle')
                    ->label('Vehicle')
                    ->boolean()
                    ->toggleable(),
                Tables\Columns\TextColumn::make('vehicle_type')
                    ->label('Vehicle Type')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('vehicle_plate_number')
                    ->label('Plate Number')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'confirmed' => 'success',
                        'cancelled' => 'danger',
                        default => 'secondary',
                    }),
                Tables\Columns\TextColumn::make('rebooking_status')
                    ->label('Rebooking')
                    ->badge()
                    ->color(fn (?string $state): string => match ($state) {
                        'pending' => 'warning',
                        'verified' => 'success',
                        default => 'gray',
                    })
                    ->placeholder('—'),
                Tables\Columns\TextColumn::make('total_price')
                    ->money('PHP')
                    ->sortable(),
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
                SelectFilter::make('status')
                    ->label('Booking status')
                    ->options([
                        'pending' => 'Pending',
                        'confirmed' => 'Confirmed',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('transaction_payment_status')
                    ->label('Payment status')
                    ->options([
                        'unpaid' => 'Unpaid',
                        'pending' => 'Pending verification',
                        'paid' => 'Paid',
                        'cancelled' => 'Cancelled',
                    ])
                    ->query(function (Builder $query, array $data) {
                        if (blank($data['value'] ?? null)) {
                            return $query;
                        }

                        return $query->whereHas('transaction', function (Builder $q) use ($data) {
                            $q->where('payment_status', $data['value']);
                        });
                    }),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('verifyBooking')
                    ->label('Verify booking')
                    ->icon('heroicon-m-check')
                    ->button()
                    ->form([
                        Forms\Components\TextInput::make('confirmation_url')
                            ->label('Confirmation URL')
                            ->url()
                            ->placeholder('https://example.com/ticket/ABC123'),
                        Forms\Components\FileUpload::make('confirmation_pdf')
                            ->label('Confirmation PDF')
                            ->directory('receipts')
                            ->acceptedFileTypes(['application/pdf'])
                            ->maxSize(10240),
                    ])
                    ->visible(fn (Booking $record): bool => $record->status === 'pending')
                    ->disabled(fn (Booking $record): bool => ! $record->transaction || $record->transaction->created_at->greaterThan(now()->subMinutes(10)))
                    ->tooltip(fn (Booking $record): ?string => $record->transaction && $record->transaction->created_at->greaterThan(now()->subMinutes(10))
                        ? 'This booking can be verified after 10 minutes from payment submission.'
                        : null)
                    ->requiresConfirmation()
                    ->action(function (Booking $record, array $data): void {
                        if (empty($data['confirmation_url']) && empty($data['confirmation_pdf'])) {
                            throw new \Exception('Please provide either a confirmation URL or upload a PDF before verifying.');
                        }

                        $ticketUrl = $data['confirmation_url'] ?? null;
                        $confirmationPdfPath = null;
                        $receiptPath = null;
                        $receiptDisk = null;

                        if (! empty($data['confirmation_pdf'])) {
                            $pdfPath = is_string($data['confirmation_pdf'])
                                ? $data['confirmation_pdf']
                                : $data['confirmation_pdf']->storeAs('receipts', 'rebooking-' . $record->transaction_number . '.pdf', 'public');
                            $confirmationPdfPath = $pdfPath;

                            $receiptDisk = 'public';
                            $receiptPath = Storage::disk('public')->path($pdfPath);

                            $record->transaction?->update(['confirmation_pdf' => $pdfPath]);
                        }

                        $record->transaction?->update(['confirmation_url' => $ticketUrl]);

                        $record->update([
                            'verified_by_user_id' => Auth::id(),
                            'verified_at' => now(),
                        ]);

                        if ($record->rebooking_status === 'pending') {
                            $record->verifyRebooking($ticketUrl, $receiptPath, $receiptDisk);
                        } else {
                            $record->update(['status' => 'confirmed']);
                            $record->transaction?->update([
                                'payment_status' => 'paid',
                                'confirmation_url' => $ticketUrl,
                                'confirmation_pdf' => $confirmationPdfPath,
                                'verified_by_user_id' => Auth::id(),
                                'verified_at' => now(),
                            ]);
                            app(\App\Services\GraciaPointsService::class)->awardPointsForBooking($record, auth()->user());
                        }
                     })
                     ->color('success'),

                Tables\Actions\Action::make('verifyRebooking')
                    ->label('Verify rebooking')
                    ->icon('heroicon-m-arrow-path')
                    ->button()
                    ->visible(fn (Booking $record): bool => $record->is_rebooked && $record->rebooking_status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (Booking $record): void {
                        $record->verifyRebooking(
                            $record->transaction?->confirmation_url,
                            $record->transaction?->confirmation_pdf ? Storage::disk('public')->path($record->transaction->confirmation_pdf) : null,
                            $record->transaction?->confirmation_pdf ? 'public' : null,
                        );
                    })
                    ->color('secondary'),
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
            TransportClassesRelationManager::class,
            AccommodationsRelationManager::class,
            PassengersRelationManager::class,
        ];
    }

    public static function getEloquentQuery(): \Illuminate\Database\Eloquent\Builder
    {
        return parent::getEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ])
            ->with(['transaction', 'user'])
            ->reorder();
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListBookings::route('/'),
            'create' => Pages\CreateBooking::route('/create'),
            'edit' => Pages\EditBooking::route('/{record}/edit'),
            'view' => Pages\ViewBooking::route('/{record}'),
        ];
    }
}
