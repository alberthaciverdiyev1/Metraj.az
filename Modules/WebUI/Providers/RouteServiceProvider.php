<?php

namespace Modules\WebUI\Providers;

use Illuminate\Foundation\Support\Providers\RouteServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Route;

class RouteServiceProvider extends ServiceProvider
{
    protected string $name = 'WebUI';

    public function boot(): void
    {
        $cssSource = module_path('WebUI', 'Resources/css');
        $jsSource = module_path('WebUI', 'Resources/js');
        $imageSource = module_path('WebUI', 'Resources/images');
        $cssTarget = public_path('webui/css');
        $jsTarget = public_path('webui/js');
        $imageTarget = public_path('webui/images');

        if (!file_exists($cssTarget)) {
            symlink($cssSource, $cssTarget);
        }

        if (!file_exists($jsTarget)) {
            symlink($jsSource, $jsTarget);
        }

        if (!file_exists($imageTarget)) {
            symlink($imageSource, $imageTarget);
        }


    }


    public function map(): void
    {
        $this->mapWebRoutes();
    }

    protected function mapWebRoutes(): void
    {
        Route::middleware('web')->group(module_path($this->name, '/Routes/web.php'));
    }

}
