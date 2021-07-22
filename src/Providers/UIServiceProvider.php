<?php

namespace Latus\UI\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Latus\UI\Repositories\Contracts\PageSettingRepository as PageSettingRepositoryContract;
use Latus\UI\Repositories\Eloquent\PageSettingRepository;

class UIServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

        if (!$this->app->bound(PageSettingRepositoryContract::class)) {
            $this->app->bind(PageSettingRepositoryContract::class, PageSettingRepository::class);
        }

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
        $this->loadMigrationsFrom(__DIR__ . '/../database/migrations');
    }
}
