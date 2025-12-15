<?php
namespace Tekquora\Watermark;

use Illuminate\Support\ServiceProvider;
use Tekquora\Watermark\Events\ImageUploaded;
use Tekquora\Watermark\Listeners\ApplyWatermarkListener;

class WatermarkServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->mergeConfigFrom(__DIR__.'/../config/watermark.php', 'watermark');
    }

    public function boot()
    {
        // Config
        $this->publishes([
            __DIR__.'/../config/watermark.php' => config_path('watermark.php'),
        ], 'watermark-config');

        // Migrations
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'watermark');

        // Routes
        $this->loadRoutesFrom(__DIR__.'/Http/routes.php');

        // Events
        $this->app['events']->listen(
            ImageUploaded::class,
            ApplyWatermarkListener::class
        );
    }
}
