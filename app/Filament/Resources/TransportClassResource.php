<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransportClassResource\Pages;
use App\Models\TransportClass;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Forms\Form;
use Filament\Forms\Components\Select;
use Filament\Resources\Resource;
use Filament\Tables;
use Illuminate\Support\Facades\Auth;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class TransportClassResource extends Resource
{
    protected static ?string $model = TransportClass::class;

    protected static ?string $navigationIcon = 'heroicon-o-ticket';

    protected static ?string $navigationGroup = 'Travel';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Transport Classes';
    protected static bool $shouldRegisterNavigation = true;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('airline_seats');
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
                TextInput::make('name')
                    ->label('Class name')
                    ->placeholder('e.g. Economy, Business Class, Tourist')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Select::make('mode')
                    ->label('Mode')
                    ->options([
                        'airline' => 'Airline',
                        'ferry' => 'Ferry',
                    ])
                    ->default('airline')
                    ->reactive()
                    ->required(),

                Select::make('operator')
                    ->label('Operator')
                    ->options(fn (callable $get) => match ($get('mode')) {
                        'airline' => collect(config('airline_seating.operators', []))
                            ->keys()
                            ->mapWithKeys(fn ($operator) => [$operator => $operator])
                            ->toArray(),
                        'ferry' => \App\Models\Vehicle::query()
                            ->where('type', 'ferry')
                            ->where('is_active', true)
                            ->distinct()
                            ->orderBy('operator')
                            ->pluck('operator', 'operator')
                            ->toArray(),
                        default => [],
                    })
                    ->placeholder('Select operator')
                    ->searchable()
                    ->required()
                    ->reactive()
                    ->columnSpanFull(),

                TextInput::make('code')
                    ->label('Class Code')
                    ->placeholder('e.g. ECO, BIZ')
                    ->maxLength(10),

                Textarea::make('description')
                    ->placeholder('Class details, amenities, etc.')
                    ->rows(3)
                    ->columnSpanFull(),

                TextInput::make('price')
                    ->label('Price (₱)')
                    ->numeric()
                    ->prefix('₱')
                    ->minValue(0)
                    ->required(),

                Toggle::make('is_active')
                    ->label('Visible to clients when booking')
                    ->default(true),

            ])
            ->columns(2);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('images')
                    ->label('Photo')
                    ->getStateUsing(fn (TransportClass $record) => $record->cover_image)
                    ->square(),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('mode')
                    ->label('Mode')
                    ->formatStateUsing(fn (?string $state): string => $state === 'ferry' ? 'Ferry' : 'Airline')
                    ->sortable(),
                TextColumn::make('operator')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('price')
                    ->money('PHP')
                    ->sortable(),
                ToggleColumn::make('is_active')
                    ->label('Active'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('operator')
                    ->label('Operator')
                    ->options(fn () => TransportClass::query()
                        ->whereNotNull('operator')
                        ->where('operator', '!=', '')
                        ->distinct()
                        ->orderBy('operator')
                        ->pluck('operator', 'operator')
                        ->toArray()
                    )
                    ->placeholder('All Operators')
                    ->form([
                        ToggleButtons::make('operator')
                            ->options(fn () => TransportClass::query()
                                ->whereNotNull('operator')
                                ->where('operator', '!=', '')
                                ->distinct()
                                ->orderBy('operator')
                                ->pluck('operator', 'operator')
                                ->toArray()
                            )
                            ->inline()
                            ->grouped(),
                    ]),
            ])
            ->filtersFormColumns(2)
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
            'index' => Pages\ListTransportClasses::route('/'),
            'create' => Pages\CreateTransportClass::route('/create'),
            'edit' => Pages\EditTransportClass::route('/{record}/edit'),
        ];
    }
}
