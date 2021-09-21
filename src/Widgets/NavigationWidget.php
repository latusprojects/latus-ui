<?php


namespace Latus\UI\Widgets;


use Illuminate\Support\Collection;
use Illuminate\View\View;
use Latus\UI\Components\WidgetComponent;
use Latus\UI\Navigation\Builder;

abstract class NavigationWidget extends WidgetComponent implements Contracts\NavigationWidget
{
    /**
     * Reference to the current navigation item, mostly for use in frontend
     *
     * @var string|null
     */
    protected string|null $reference;
    protected Builder $builder;

    public function &builder(): Builder
    {
        if (!isset($this->{'builder'})) {
            $this->builder = new Builder();
        }

        return $this->builder;
    }

    public function resolvesView(): View|null
    {
        return null;
    }


    public function resolvesData(): Collection|array|null
    {
        return $this->builder()->compileItems();
    }

    public function register()
    {
    }

    public function setReference(string $reference)
    {
        $this->reference = $reference;
    }

    public function getReference(): string|null
    {
        return $this->reference;
    }
}