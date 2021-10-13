<?php

namespace Latus\UI\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Latus\UI\Components\Contracts\ModuleComponent;

class ResolveCurrentModule
{
    /**
     * Handle an incoming request.
     *
     * @param Request $request
     * @param Closure $next
     * @param string $currentModuleContract
     * @return mixed
     */
    public function handle(Request $request, Closure $next, string $currentModuleContract): mixed
    {
        if (!app()->bound(ModuleComponent::class)) {
            app()->bind(ModuleComponent::class, $currentModuleContract);
        }

        if (!defined('CURRENT_MODULE')) {
            define('CURRENT_MODULE', $currentModuleContract);
        }

        return $next($request);
    }
}