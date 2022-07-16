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


        $this->app->booted(function () {
            Site::routes(__DIR__.'/../routes/web.php');
        });
    }
}
