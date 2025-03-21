<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class ComponentServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        Blade::componentNamespace('App\\View\\Components', 'app');
        
        // Register anonymous components from resources/views/components
        Blade::anonymousComponentPath(resource_path('views/components'), '');
    }
} 