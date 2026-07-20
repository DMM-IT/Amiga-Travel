<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserLoginHistoryResource\Pages;
use App\Models\UserLoginHistory;
use App\Models\User;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class UserLoginHistoryResource extends Resource
{
    protected static ?string $model = UserLoginHistory::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';

    protected static ?string $navigationGroup = 'Administration';

    protected static ?int $navigationSort = 4;

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->hasAdminPermission('manage_users');
    }

    public static function form(Form $form): Form
    {
        return $form->schema([
            TextInput::make('email')->label('Email')->disabled(),
            TextInput::make('login_type')->label('Login type')->disabled(),
            TextInput::make('ip_address')->label('IP address')->disabled(),
            Textarea::make('user_agent')->label('User agent')->disabled(),
            Toggle::make('success')->label('Success')->disabled(),
            Textarea::make('description')->label('Description')->disabled(),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                TextColumn::make('login_type')
                    ->label('Login type')
                    ->sortable(),
                TextColumn::make('ip_address')
                    ->label('IP address')
                    ->sortable(),
                TextColumn::make('user_agent')
                    ->label('User agent')
                    ->limit(60),
                BooleanColumn::make('success')
                    ->label('Success'),
                TextColumn::make('description')
                    ->label('Description')
                    ->limit(60),
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('success')
                    ->label('Result')
                    ->options([
                        1 => 'Success',
                        0 => 'Failure',
                    ]),
                SelectFilter::make('login_type')
                    ->label('Type')
                    ->options([
                        'web_login' => 'Web login',
                        'api_login' => 'API login',
                        'web_register' => 'Web signup',
                        'api_register' => 'API signup',
                    ]),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListUserLoginHistories::route('/'),
            'view' => Pages\ViewUserLoginHistory::route('/{record}'),
        ];
    }
}
