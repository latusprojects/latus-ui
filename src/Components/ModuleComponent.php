<?php


namespace Latus\UI\Components;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use \Latus\UI\Components\Contracts\ModuleComponent as ModuleComponentContract;
use Latus\UI\Components\Contracts\PageComponent;

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
        $this->register();
        app()->singleton(static::class, $this);
    }

    /**
     * @throws BindingResolutionException
     */
    public function getPage(string $name): PageComponent
    {
        if ($this->pages->has($name)) {
            return app()->make($this->pages->get($name));
        }

        return app()->make($this->pages->first());
    }
}