<?php

namespace Tekquora\Watermark;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;
use Tekquora\Watermark\Events\ImageUploaded;
use Tekquora\Watermark\Listeners\ApplyWatermarkListener;

class WatermarkServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(
            __DIR__.'/../config/watermark.php',
            'watermark'
        );
    }

    public function boot()
    {
        // Publish config
        $this->publishes([
            __DIR__.'/../config/watermark.php' => config_path('watermark.php'),
        ], 'watermark-config');

        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'watermark');

        // Routes
        $this->registerRoutes();

        // Events
        $this->app['events']->listen(
            ImageUploaded::class,
            ApplyWatermarkListener::class
        );
    }

    protected function registerRoutes(): void
    {
        $config = config('watermark.route');

        if (!$config['enabled']) {
            return;
        }

        // Host app overrides routes completely
        if (is_callable($config['custom_routes'])) {
            call_user_func($config['custom_routes']);
            return;
        }

        Route::group([
            'prefix' => $config['prefix'],
            'middleware' => $config['middleware'],
            'as' => $config['name_prefix'],
        ], function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }
}
