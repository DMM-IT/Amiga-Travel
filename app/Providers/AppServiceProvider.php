<?php

namespace App\Providers;

use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        // Cache header & footer settings — fetched on every single page load.
        // TTL: 1 hour. Cleared automatically when admin saves website settings.
        View::composer('layouts.app', function ($view) {
            $header = Cache::remember('website_settings:header', now()->addHour(), fn () =>
                WebsiteSetting::firstWhere('page', 'header')
            );
            $footer = Cache::remember('website_settings:footer', now()->addHour(), fn () =>
                WebsiteSetting::firstWhere('page', 'footer')
            );

            $view->with('headerData', $header?->header_data ?? []);
            $view->with('footerData', $footer?->footer_data ?? []);
        });
    }
}
