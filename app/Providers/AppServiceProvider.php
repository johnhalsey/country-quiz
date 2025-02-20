<?php

namespace App\Providers;

use Illuminate\Support\Facades\Vite;
use App\Services\CountriesNowService;
use Illuminate\Support\ServiceProvider;
use App\Contracts\CountryServiceInterface;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->bind(CountryServiceInterface::class, CountriesNowService::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Vite::prefetch(concurrency: 3);
    }
}
