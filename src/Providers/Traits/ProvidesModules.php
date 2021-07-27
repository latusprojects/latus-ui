<?php


namespace Latus\UI\Providers\Traits;


trait ProvidesModules
{
    use RequiresComponentService;

    protected function provideModules(array $moduleClasses)
    {
        foreach ($moduleClasses as $moduleContract => $moduleClass) {
            $this->getComponentService()->provideModule($moduleContract, $moduleClass);
        }

    }
}