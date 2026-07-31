<?php

namespace App\Providers;

use App\Console\Commands\PurgeExpiredSchedules;
use App\Models\WebsiteSetting;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;
use Symfony\Component\Mailer\Bridge\Sendgrid\Transport\SendgridTransportFactory;
use Symfony\Component\Mailer\Transport\Dsn;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([
                PurgeExpiredSchedules::class,
            ]);
        }
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
            $headerData = Cache::remember('website_settings:header_data', now()->addHour(), fn () =>
                WebsiteSetting::firstWhere('page', 'header')?->header_data ?? []
            );
            $footerData = Cache::remember('website_settings:footer_data', now()->addHour(), fn () =>
                WebsiteSetting::firstWhere('page', 'footer')?->footer_data ?? []
            );

            $view->with('headerData', $headerData);
            $view->with('footerData', $footerData);
        });

        Mail::extend('sendgrid', function (array $config) {
            return (new SendgridTransportFactory)->create(
                new Dsn(
                    'sendgrid+api',
                    'default',
                    $config['api_key'] ?? env('SENDGRID_API_KEY')
                )
            );
        });
    }
}
