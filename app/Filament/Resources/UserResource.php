<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Get;
use Filament\Forms\Components\CheckboxList;
use Illuminate\Support\Facades\Auth;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Hash;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static ?string $navigationIcon = 'heroicon-o-users';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_users');
    }

    protected static ?string $navigationLabel = 'Staff Accounts';

    protected static ?string $navigationGroup = 'Administration';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                TextInput::make('email')
                    ->email()
                    ->required()
                    ->unique(ignoreRecord: true)
                    ->maxLength(255),
                TextInput::make('password')
                    ->label('Password')
                    ->password()
                    ->required(fn (?Pages\EditUser $livewire): bool => $livewire instanceof Pages\CreateUser)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->same('password')
                    ->dehydrated(false)
                    ->required(fn (?Pages\EditUser $livewire): bool => $livewire instanceof Pages\CreateUser),
                Toggle::make('is_staff')
                    ->label('Staff account')
                    ->default(false)
                    ->helperText('Enable this if this user should be able to log into the admin panel.'),
                Toggle::make('is_admin')
                    ->label('Administrator account')
                    ->default(false)
                    ->helperText('Administrator accounts bypass permission checks and can access every feature.')
                    ->visible(fn (Get $get): bool => $get('is_staff'))
                    ->reactive(),
                CheckboxList::make('admin_permissions')
                    ->label('Admin permissions')
                    ->options(User::ADMIN_PERMISSIONS)
                    ->columns(2)
                    ->helperText('Choose the admin features this staff user may access.')
                    ->visible(fn (Get $get): bool => $get('is_staff'))
                    ->disabled(fn (Get $get): bool => $get('is_admin')),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('email')
                    ->searchable()
                    ->sortable(),
                BooleanColumn::make('is_staff')
                    ->label('Staff')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }
}
