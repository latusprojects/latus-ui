<?php


namespace Latus\UI\Providers;


use Illuminate\Support\ServiceProvider;
use Latus\UI\Providers\Traits\DefinesModules;
use Latus\UI\Providers\Traits\ProvidesModules;
use Latus\UI\Providers\Traits\ProvidesWidgets;

abstract class ComponentServiceProvider extends ServiceProvider
{
    use ProvidesModules, DefinesModules, ProvidesWidgets;
}