<?php

namespace App\Providers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        // Make sure there's no funny business
        if (config('app.env') !== 'production') {
            Model::preventLazyLoading();
        }

        // Register marketplace anonymous component path
        Blade::anonymousComponentPath(resource_path('views/components'), 'component');
    }
}
