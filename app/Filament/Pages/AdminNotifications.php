<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class AdminNotifications extends Page
{
    protected static ?string $navigationIcon = 'heroicon-o-bell';

    protected static ?string $navigationGroup = 'Notifications';

    protected static ?int $navigationSort = 1;

    protected static ?string $navigationLabel = 'Notifications';

    protected static ?string $title = 'Notifications';

    protected static string $view = 'filament.pages.admin-notifications';

    public static function canAccess(): bool
    {
        $user = Auth::user();

        return $user instanceof User && $user->isStaff();
    }
}
