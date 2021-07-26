<?php


namespace Latus\UI\Services;


use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Repositories\Contracts\ComponentRepository;

class ComponentService
{
    public function __construct(
        protected ComponentRepository $componentRepository
    )
    {
    }

    public function defineModule(string $moduleContract)
    {
        $this->componentRepository->defineModule($moduleContract);
    }

    public function provideModule(string $moduleContract, string $moduleClass)
    {
        $this->componentRepository->provideModule($moduleContract, $moduleClass);
    }

    public function provideWidget(string $widgetClass, string $widgetName)
    {
        $this->componentRepository->provideWidget($widgetClass, $widgetName);
    }

    public function getActiveModule(string $moduleContract): ModuleComponent|null
    {
        return $this->componentRepository->getActiveModule($moduleContract);
    }

    public function setActiveModule(string $moduleContract, string $moduleClass)
    {
        $this->componentRepository->setActiveModule($moduleContract, $moduleClass);
    }

    public function getDisabledModules(): array
    {
        return $this->componentRepository->getDisabledModules();
    }

    public function disableModule(string $moduleContract)
    {
        $this->componentRepository->disableModule($moduleContract);
    }

    public function enableModule(string $moduleContract)
    {
        $this->componentRepository->enableModule($moduleContract);
    }

}