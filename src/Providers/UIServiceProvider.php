<?php

namespace Latus\UI\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;

class UIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        $this->app->singleton('widgets', function () {
            return new Collection();
        });

        $this->app->singleton('modules', function () {
            return new Collection();
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
    }
}
