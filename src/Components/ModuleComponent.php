<?php


namespace Latus\UI\Components;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Support\Collection;
use Illuminate\View\View;
use \Latus\UI\Components\Contracts\ModuleComponent as ModuleComponentContract;
use Latus\UI\Components\Contracts\PageComponent;
use Symfony\Component\ErrorHandler\Error\ClassNotFoundError;

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
        foreach ($pages as $pageType => $pageClass) {
            $this->pages->put($pageType, $pageClass);
        }
    }

    public function compose()
    {
        $this->register();
    }

    /**
     * @throws ClassNotFoundError
     */
    public function getPage(string $name): PageComponent
    {
        if ($this->pages->has($name)) {
            $pageClass = $this->pages->get($name);
            return new $pageClass($this);
        }

        $pageClass = $this->pages->first();
        return new $pageClass($this);
    }
}