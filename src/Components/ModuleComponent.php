<?php


namespace Latus\UI\Components;

use Illuminate\Support\Collection;
use Illuminate\View\View;
use \Latus\UI\Components\Contracts\ModuleComponent as ModuleComponentContract;

abstract class ModuleComponent extends Component implements ModuleComponentContract
{
    protected Collection $pages;

    public function __construct()
    {
        $this->pages = new Collection();
    }

    public function resolvesView(): View|null
    {
        return null;
    }

    public function definePages(array $pages)
    {
        $this->pages->merge($pages);
    }

    public function compose()
    {
        app()->singleton(static::class, $this);
    }
}