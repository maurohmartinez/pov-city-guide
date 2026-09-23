<?php

namespace App\Providers;

use App\Services\SettingService;
use App\View\Composers\MenuComposer;
use Backpack\Settings\app\Models\Setting;
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
        $macrosPath = app_path('Helpers/macros.php');
        if (file_exists($macrosPath)) {
            include_once($macrosPath);
        }

        if (config('app.env') !== 'production') {
            Model::preventLazyLoading();
        }

        Blade::anonymousComponentPath(resource_path('views/components'), 'component');

        View::composer(['inc.menu', 'inc.stripe-menu'], MenuComposer::class);

        View::composer('inc.footer', function (\Illuminate\View\View $view) {
            return $view->with('socialMediaLinks', json_decode(Setting::get(SettingService::SOCIAL_MEDIA_LINKS), true));
        });
    }
}
