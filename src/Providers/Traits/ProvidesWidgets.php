<?php


namespace Latus\UI\Providers\Traits;


use Latus\Laravel\Http\Middleware\BuildPackageDependencies;

trait ProvidesWidgets
{
    use RequiresComponentService;

    protected function provideWidgets(array $widgetClasses)
    {
        BuildPackageDependencies::addDependencyClosure(function () use ($widgetClasses) {
            foreach ($widgetClasses as $widgetName => $widgetClass) {
                $this->getComponentService()->provideWidget($widgetClass, $widgetName);
            }
        });
    }
}