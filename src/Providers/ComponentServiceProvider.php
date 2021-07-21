<?php


namespace Latus\UI\Providers;


use Illuminate\Support\ServiceProvider;
use Latus\UI\Components\ComponentRepository;

abstract class ComponentServiceProvider extends ServiceProvider
{

    protected ComponentRepository $componentRepository;

    public function __construct($app)
    {
        parent::__construct($app);

        $this->componentRepository = new ComponentRepository();
    }

    protected function defineModules(array $modules)
    {
        foreach ($modules as $module_class) {
            $this->componentRepository->defineModule(app()->make($module_class));
        }
    }

    protected function defineWidgets(array $widgets)
    {
        foreach ($widgets as $widget_class) {
            $this->componentRepository->defineWidget(app()->make($widget_class));
        }
    }
}