<?php


namespace Latus\UI\Providers\Traits;


use Latus\Laravel\Http\Middleware\BuildPackageDependencies;

trait DefinesModules
{
    use RequiresComponentService;

    protected function defineModules(array $module_contracts)
    {
        BuildPackageDependencies::addDependencyClosure(function () use ($module_contracts) {
            foreach ($module_contracts as $moduleContract => $info) {
                $this->getComponentService()->defineModule($moduleContract, $info);
            }
        });
    }
}