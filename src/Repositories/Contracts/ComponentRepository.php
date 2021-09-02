<?php


namespace Latus\UI\Repositories\Contracts;

use Latus\UI\Components\Contracts\ModuleComponent;

interface ComponentRepository
{
    public function defineModule(string $moduleContract, array $info);

    public function getModuleInfo(string $moduleContract): array;

    public function provideWidget(string $widgetClass, string $widgetName);

    public function createModuleBinding(string $moduleContract, string $moduleClass): ModuleComponent|null;

    public function getActiveModule(string $moduleContract): ModuleComponent|bool|null;

    public function getDisabledModules(): array;

    public function disableModule(string $moduleContract);

    public function enableModule(string $moduleContract);

    public function getActiveModules(): array;
}