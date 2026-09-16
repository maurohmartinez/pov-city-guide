<?php

namespace App\Providers;

use App\View\Composers\MenuComposer;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
    }

    public function boot(): void
    {
        if (config('app.env') !== 'production') {
            Model::preventLazyLoading();
        }

        Blade::anonymousComponentPath(resource_path('views/components'), 'component');

        View::composer(['inc.menu', 'inc.hero'], MenuComposer::class);

        $macrosPath = app_path('Helpers/macros.php');
        if (file_exists($macrosPath)) {
            include_once($macrosPath);
        }
    }
}
