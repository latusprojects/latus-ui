<?php


namespace Latus\UI\Repositories\Contracts;

use Latus\UI\Components\Contracts\ModuleComponent;

interface ComponentRepository
{
    public function defineModule(string $moduleContract);

    public function provideModule(string $moduleContract, string $moduleClass);

    public function defineWidget(string $widgetClass);

    public function getActiveModule(string $moduleContract): ModuleComponent|null;

    public function setActiveModule(string $moduleContract, string $moduleClass);

    public function getDisabledModules(): array;

    public function disableModule(string $moduleContract);

    public function enableModule(string $moduleContract);
}