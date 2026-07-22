<?php

namespace App\Providers\Filament;

use App\Filament\Pages\ManageWebsiteSettings;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Support\Facades\FilamentView;
use Filament\View\PanelsRenderHook;
use Filament\Widgets;
use Illuminate\View\View;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->brandName('AMIGA GRACIA')
            ->brandLogo(new \Illuminate\Support\HtmlString('
                <style>
                    /* Force the Filament logo wrapper to always display so the icon stays visible when collapsed */
                    .fi-sidebar-header > div:first-child {
                        display: flex !important;
                        opacity: 1 !important;
                        min-width: 0;
                    }
                    /* Hide scrollbar in the sidebar navigation */
                    .fi-sidebar-nav {
                        scrollbar-width: none !important;
                    }
                    .fi-sidebar-nav::-webkit-scrollbar {
                        display: none !important;
                    }
                    /* Add visible border to separate sidebar from main content */
                    .fi-sidebar {
                        border-right: 1px solid rgba(0, 0, 0, 0.08) !important;
                    }
                    .dark .fi-sidebar {
                        border-right: 1px solid rgba(255, 255, 255, 0.12) !important;
                    }
                </style>
                <div class="flex items-center gap-3 w-full overflow-hidden">
                    <img src="' . asset('images/amiga-logo-transparent.png') . '" alt="Amiga Gracia" class="h-7 w-auto shrink-0" />
                    <span class="font-bold uppercase tracking-wider text-gray-950 dark:text-white whitespace-nowrap transition-all duration-300" x-show="$store.sidebar.isOpen" x-cloak>AMIGA GRACIA</span>
                </div>
            '))
            ->brandLogoHeight('2.5rem')
            ->sidebarCollapsibleOnDesktop()
            ->favicon(asset('images/amiga-logo-transparent.png'))
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Pages\Dashboard::class,
                ManageWebsiteSettings::class,
            ])
            ->renderHook(PanelsRenderHook::GLOBAL_SEARCH_AFTER, function (): View {
                return view('filament.admin.notification-bell');
            })
            ->renderHook(
                PanelsRenderHook::SCRIPTS_BEFORE,
                fn (): string => '<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>'
            )
            ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
            ->widgets([
                // Removed AccountWidget
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}
