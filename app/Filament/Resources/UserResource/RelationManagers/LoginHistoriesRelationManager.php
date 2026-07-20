<?php

namespace App\Filament\Resources\UserResource\RelationManagers;

use App\Models\UserLoginHistory;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Columns\BooleanColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class LoginHistoriesRelationManager extends RelationManager
{
    protected static string $relationship = 'loginHistories';

    protected static ?string $recordTitleAttribute = 'login_type';

    public function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('login_type')->label('Type')->sortable(),
                TextColumn::make('ip_address')->label('IP')->sortable(),
                TextColumn::make('user_agent')->label('User agent')->limit(60),
                BooleanColumn::make('success')->label('Success'),
                TextColumn::make('description')->label('Description')->limit(80),
                TextColumn::make('created_at')->label('When')->dateTime()->sortable(),
            ])
            ->headerActions([])
            ->actions([
                Tables\Actions\ViewAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }
}
