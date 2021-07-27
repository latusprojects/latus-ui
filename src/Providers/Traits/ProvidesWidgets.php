<?php


namespace Latus\UI\Providers\Traits;


trait ProvidesWidgets
{
    use RequiresComponentService;

    protected function provideWidgets(array $widgetClasses)
    {
        foreach ($widgetClasses as $widgetName => $widgetClass) {
            $this->getComponentService()->provideWidget($widgetClass, $widgetName);
        }
    }
}