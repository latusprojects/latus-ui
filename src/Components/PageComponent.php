<?php


namespace Latus\UI\Components;

use Illuminate\Support\Collection;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Components\Contracts\PageComponent as PageComponentContract;
use Latus\UI\Services\PageSettingService;

abstract class PageComponent extends Component implements PageComponentContract
{
    public function __construct(
        protected ModuleComponent &$moduleComponent,
        protected mixed $content = null
    )
    {
    }

    public function getSettings(): Collection
    {
        return (app(PageSettingService::class))->getSettings($this->module()->getName(), $this->getName());
    }

    public function register()
    {
    }

    public function compose()
    {
    }

    public function module(): ModuleComponent
    {
        return $this->moduleComponent;
    }

    public function getContent(): mixed
    {
        return $this->content;
    }

    public function setContent(mixed $content)
    {
        $this->content = $content;
    }
}