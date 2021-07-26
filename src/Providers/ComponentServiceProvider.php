<?php


namespace Latus\UI\Providers;


use Illuminate\Support\ServiceProvider;
use Latus\UI\Services\ComponentService;

abstract class ComponentServiceProvider extends ServiceProvider
{

    public function __construct(
        $app,
        protected ComponentService $componentService
    )
    {
        parent::__construct($app);
    }

    protected function defineModules(array $module_contracts)
    {
        foreach ($module_contracts as $moduleContract) {
            $this->componentService->defineModule($moduleContract);
        }
    }

    protected function provideModules(array $moduleClasses)
    {
        foreach ($moduleClasses as $moduleContract => $moduleClass) {
            $this->componentService->provideModule($moduleContract, $moduleClass);
        }

    }

    protected function provideWidgets(array $widgetClasses)
    {
        foreach ($widgetClasses as $widgetName => $widgetClass) {
            $this->componentService->provideWidget($widgetClass, $widgetName);
        }
    }
}