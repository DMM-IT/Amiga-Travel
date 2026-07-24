<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Models\User;
use Filament\Forms\Get;
use Filament\Forms\Components\Checkbox;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Section;
use Illuminate\Support\Facades\Auth;
use App\Filament\Resources\UserResource\RelationManagers\BookingsRelationManager;
use App\Filament\Resources\UserResource\RelationManagers\LoginHistoriesRelationManager;
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

        return $user instanceof User && $user->hasAdminPermission('staff_accounts');
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
                    ->required(fn (?object $livewire): bool => $livewire instanceof Pages\CreateUser)
                    ->dehydrated(fn (?string $state): bool => filled($state))
                    ->dehydrateStateUsing(fn (?string $state): ?string => filled($state) ? Hash::make($state) : null),
                TextInput::make('password_confirmation')
                    ->label('Confirm Password')
                    ->password()
                    ->same('password')
                    ->dehydrated(false)
                    ->required(fn (?object $livewire): bool => $livewire instanceof Pages\CreateUser),
                Hidden::make('is_staff')
                    ->default(true),
                Toggle::make('is_admin')
                    ->label('Administrator account')
                    ->default(false)
                    ->helperText('Administrator accounts bypass permission checks and can access every feature.')
                    ->reactive(),
                Placeholder::make('admin_permission_note')
                    ->content('Administrators have access to every feature.')
                    ->visible(fn (Get $get): bool => (bool) $get('is_admin')), 
                Section::make('Staff features')
                    ->schema([
                        ...self::buildPermissionGroups(),
                        Placeholder::make('staff_permissions_helper')
                            ->content('Choose which admin features this staff user can access.'),
                    ])
                    ->columnSpanFull(),
            ]);
    }

    protected static function buildPermissionGroups(): array
    {
        $groups = [];

        foreach (User::getPermissionGroups() as $groupLabel => $permissions) {
            $checkboxes = [];

            foreach ($permissions as $permissionKey => $label) {
                $checkboxes[] = Checkbox::make("staff_permissions.{$permissionKey}")
                    ->label($label)
                    ->inline(false)
                    ->disabled(fn (Get $get): bool => (bool) $get('is_admin'));
            }

            $groups[] = Section::make($groupLabel)
                ->schema([
                    Grid::make(['default' => 1, 'lg' => 2])
                        ->schema($checkboxes),
                ])
                ->columnSpanFull();
        }

        return $groups;
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
            LoginHistoriesRelationManager::class,
            BookingsRelationManager::class,
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
