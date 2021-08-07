<?php


namespace Latus\UI\Providers\Traits;


use Latus\UI\Services\ComponentService;

trait RequiresComponentService
{
    protected ComponentService $componentService;

    public function getComponentService(): ComponentService
    {
        if (!isset($this->{'componentService'})) {
            $this->componentService = $this->app->make(ComponentService::class);
        }

        return $this->componentService;
    }
}