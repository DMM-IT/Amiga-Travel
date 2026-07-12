<?php

namespace App\Providers;

use App\Models\WebsiteSetting;
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

        View::composer('layouts.app', function ($view) {
            $header = WebsiteSetting::firstWhere('page', 'header');
            $footer = WebsiteSetting::firstWhere('page', 'footer');

            $view->with('headerData', $header->header_data ?? []);
            $view->with('footerData', $footer->footer_data ?? []);
        });
    }
}
