<?php


namespace Latus\UI\Providers\Traits;


use Illuminate\Support\Facades\Route;
use Latus\Laravel\Http\Middleware\BuildPackageDependencies;

trait DefinesModuleViews
{
    use RequiresComponentService;

    protected function defineModuleViews(string $moduleContract, array $views, array $middleware = [])
    {
        BuildPackageDependencies::addDependencyClosure(function () use ($moduleContract, $views, $middleware) {
            $moduleInfo = $this->getComponentService()->getModuleInfo($moduleContract);

            $routeAlias = $moduleInfo['alias'];
            $moduleController = $moduleInfo['controller'];

            Route::middleware($middleware)->prefix($routeAlias)->group(function () use ($views, $moduleController) {

                foreach ($views as $view) {
                    $route = $view['route'];
                    $viewTarget = $view['target'];

                    Route::get($route, function () use ($moduleController, $viewTarget) {
                        return app()->call($moduleController, ['viewTarget' => $viewTarget]);
                    });
                }

            });
        });
    }
}