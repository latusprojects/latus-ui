<?php

namespace Latus\UI\Providers;

use Illuminate\Support\Collection;
use Illuminate\Support\ServiceProvider;
use Latus\UI\Providers\Traits\ProvidesWidgets;
use Latus\UI\Repositories\Contracts\PageSettingRepository as PageSettingRepositoryContract;
use Latus\UI\Repositories\Eloquent\PageSettingRepository;
use Latus\UI\Widgets\AdminNav;

class UIServiceProvider extends ServiceProvider
{
    use ProvidesWidgets;

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

        $this->provideWidgets([
            'admin-nav' => AdminNav::class
        ]);
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
