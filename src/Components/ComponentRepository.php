<?php


namespace Latus\UI\Components;


use Illuminate\Support\Collection;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Components\Contracts\WidgetComponent;

class ComponentRepository
{

    public function defineModule(ModuleComponent $moduleComponent)
    {
        if (!$this->getAllModules()->has($moduleComponent->getName())) {
            $this->getAllModules()->put($moduleComponent->getName(), $moduleComponent);
        }
    }

    public function defineWidget(WidgetComponent $widgetComponent)
    {
        $name = $widgetComponent->getDomain() ? $widgetComponent->getDomain() . '/' . $widgetComponent->getName() : $widgetComponent->getName();
        if (!$this->getAllWidgets()->has($name)) {
            $this->getAllWidgets()->put($name, $widgetComponent);
            $widgetComponent->compose();
        }
    }

    public function getAllModules(): Collection
    {
        return app('modules');
    }

    public function getAllWidgets(): Collection
    {
        return app('widgets');
    }

    public function getModule(string $name): ModuleComponent|null
    {
        return $this->getAllModules()->get($name);
    }

    public function getWidget(string $name, string $domain = null): WidgetComponent|null
    {
        $name = $domain ? $domain . '/' . $name : $name;

        return $this->getAllWidgets()->get($name);
    }
}