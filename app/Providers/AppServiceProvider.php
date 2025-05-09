<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
use Facebook\Url\UrlDetectionHandler;
use Facebook\Url\UrlDetectionInterface;
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
        if (config('app.env') === 'production') {
            URL::forceScheme('https');
        }
    }
    public const HOME = '/redirect-by-role';
}
