<?php


namespace Latus\UI\Providers\Traits;


trait DefinesModules
{
    use RequiresComponentService;

    protected function defineModules(array $module_contracts)
    {
        foreach ($module_contracts as $moduleContract) {
            $this->getComponentService()->defineModule($moduleContract);
        }
    }
}