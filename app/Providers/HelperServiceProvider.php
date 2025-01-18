<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\StudentHelper;

class HelperServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Register the StudentHelper class as a singleton
        $this->app->singleton('student-helper', function () {
            return new StudentHelper();
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        //
    }
}