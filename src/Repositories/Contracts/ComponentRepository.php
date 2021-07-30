<?php


namespace Latus\UI\Repositories\Contracts;

use Latus\UI\Components\Contracts\ModuleComponent;

interface ComponentRepository
{
    public function defineModule(string $moduleContract);

    public function provideWidget(string $widgetClass, string $widgetName);

    public function getActiveModule(string $moduleContract): ModuleComponent|bool|null;

    public function getDisabledModules(): array;

    public function disableModule(string $moduleContract);

    public function enableModule(string $moduleContract);

    public function getActiveModules(): array;
}