<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\PersediaanBahan;
use App\Observers\PersediaanBahanObserver;


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
        // PersediaanBahan::observe(PersediaanBahanObserver::class);
    }
}
