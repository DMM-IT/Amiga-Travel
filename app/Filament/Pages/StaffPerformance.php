<?php

namespace App\Filament\Pages;

use App\Models\User;
use App\Support\ReportingService;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;

class StaffPerformance extends Page
{
    public static function canAccess(): bool
    {
        $user = Auth::user();
        return $user instanceof User && $user->is_admin;
    }

    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationGroup = 'Reports';
    protected static ?int $navigationSort = 2;
    protected static ?string $navigationLabel = 'Staff Performance';
    protected static ?string $title = 'Staff Performance Reports';
    protected static string $view = 'filament.pages.staff-performance';

    public \Illuminate\Support\Collection $staffStats;

    public function mount(): void
    {
        $service = app(ReportingService::class);
        $this->staffStats = $service->getStaffStats();
    }
}
