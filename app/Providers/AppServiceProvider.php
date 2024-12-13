<?php

namespace App\Providers;

use App\Models\Reminder; 
use Illuminate\Support\ServiceProvider;
use App\Observers\TaskObserver;

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
        Reminder::observe(TaskObserver::class);
    }
}
