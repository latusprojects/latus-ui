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

    public function defineModule(string $moduleContract, array $info)
    {
        $this->componentRepository->defineModule($moduleContract, $info);
    }

    public function getModuleInfo(string $moduleContract): array
    {
        return $this->componentRepository->getModuleInfo($moduleContract);
    }

    public function provideWidget(string $widgetClass, string $widgetName)
    {
        $this->componentRepository->provideWidget($widgetClass, $widgetName);
    }

    public function getActiveModule(string $moduleContract): ModuleComponent|null
    {
        return $this->componentRepository->getActiveModule($moduleContract);
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