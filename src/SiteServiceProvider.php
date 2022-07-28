<?php

namespace Lailen\OpenAdmin\Site;

use Illuminate\Support\ServiceProvider;

class SiteServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(Site $extension)
    {
        if (! Site::boot()) {
            return ;
        }

        if ($views = $extension->views()) {
            $this->loadViewsFrom($views, 'site');
        }

        if ($this->app->runningInConsole()) {
            $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        }

        $this->mergeConfigFrom(__DIR__.'/../config/site.php', 'site');

        $this->publishes([
                __DIR__.'/config/site.php' => config_path('site.php'),
            ], 'config');

        $this->app->booted(function () {
            Site::routes(__DIR__.'/../routes/web.php');
        });
    }
}
