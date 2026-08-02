<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransportClassResource\Pages;
use App\Models\TransportClass;
use App\Models\User;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Forms\Components\Select;
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

    protected static ?string $navigationGroup = 'Airline';
    protected static ?int $navigationSort = 1;
    protected static ?string $navigationLabel = 'Airline Seats';
    protected static bool $shouldRegisterNavigation = false;

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
                    ->placeholder('e.g. Economy, Business Class')
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

                TextInput::make('operator')
                    ->label('Operator')
                    ->placeholder('e.g. Philippine Airlines, Cebu Pacific, AirAsia')
                    ->maxLength(255)
                    ->visible(fn ($get) => $get('mode') === 'airline'),

                Select::make('operator')
                    ->label('Operator')
                    ->options([
                        '2GO' => '2GO',
                        'Starlite' => 'Starlite',
                    ])
                    ->visible(fn ($get) => $get('mode') === 'ferry')
                    ->required(fn ($get) => $get('mode') === 'ferry'),

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
            'index' => Pages\ListTransportClasses::route('/'),
            'create' => Pages\CreateTransportClass::route('/create'),
            'edit' => Pages\EditTransportClass::route('/{record}/edit'),
        ];
    }
}
