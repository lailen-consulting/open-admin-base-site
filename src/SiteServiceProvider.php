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

        if ($this->app->runningInConsole() && $assets = $extension->assets()) {
            $this->publishes(
                [$assets => public_path('vendor/lailen/site')],
                'site'
            );
        }

        if ($this->app->runningInConsole()) {
            $this->publishes(
                [__DIR__.'/../database/migrations' => database_path('migrations')],
                'lailen-site'
            );
        }


        $this->app->booted(function () {
            Site::routes(__DIR__.'/../routes/web.php');
        });
    }
}
