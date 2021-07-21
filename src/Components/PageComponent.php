<?php


namespace Latus\UI\Components;

use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Components\Contracts\PageComponent as PageComponentContract;

abstract class PageComponent extends Component implements PageComponentContract
{
    public function __construct(
        protected ModuleComponent &$moduleComponent
    )
    {
    }

    public function getModule(): ModuleComponent
    {
        return $this->moduleComponent;
    }
}