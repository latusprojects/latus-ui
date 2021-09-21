<?php

namespace Latus\UI\Navigation;

use Illuminate\Support\Collection;
use Latus\UI\Exceptions\BuilderNotDefinedException;
use Latus\UI\Exceptions\ParentNotDefinedException;
use Latus\UI\Navigation\Contracts\BuilderProvider;
use Latus\UI\Navigation\Traits\HasCompilableItems;
use Latus\UI\Navigation\Traits\PrependsAndAppendsItems;
use Latus\UI\Navigation\Traits\ProvidesBuilder;
use Latus\UI\Navigation\Traits\SupportsAuthorization;

class Item implements BuilderProvider
{
    use ProvidesBuilder, PrependsAndAppendsItems, SupportsAuthorization, HasCompilableItems;

    protected string $parentName;
    protected string $parentClass;
    protected string $parentGroupName;
    protected Collection $subItems;

    public function __construct(
        protected string                     $name,
        protected string                     $label,
        protected string                     $icon,
        protected string                     $url,
        protected string|null                $view,
        protected string|array|\Closure|null $authorize = null
    )
    {
        $this->subItems = new Collection();
    }

    /**
     * @return string
     */
    public function getLabel(): string
    {
        return $this->label;
    }

    /**
     * @param string $label
     * @return Item
     */
    public function setLabel(string $label): self
    {
        $this->label = $label;

        return $this;
    }

    /**
     * @return string
     */
    public function getIcon(): string
    {
        return $this->icon;
    }

    /**
     * @param string $icon
     * @return Item
     */
    public function setIcon(string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @param string $url
     * @return Item
     */
    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getView(): ?string
    {
        return $this->view;
    }

    /**
     * @param string|null $view
     * @return Item
     */
    public function setView(?string $view): self
    {
        $this->view = $view;

        return $this;
    }

    /**
     * @throws ParentNotDefinedException
     * @throws BuilderNotDefinedException
     */
    public function remove(): Group|Item
    {
        return $this->parent()->removeItem($this->getName());
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws BuilderNotDefinedException
     * @throws ParentNotDefinedException
     */
    public function setParent(Group|Item &$parent): void
    {
        $this->parentName = $parent->getName();
        $this->parentClass = get_class($parent);

        if ($parent instanceof Item) {
            $this->parentGroupName = $parent->relatedGroup()->getName();
        }

        $this->setBuilder($parent->builder());
    }

    /**
     * @throws ParentNotDefinedException
     * @throws BuilderNotDefinedException
     */
    public function parent(): Group|Item
    {
        if (!isset($this->{'parentName'}) || !isset($this->{'parentClass'})) {
            throw new ParentNotDefinedException('No parent was defined but is required. Items must always have a reference of a parent-instance.');
        }

        if ($this->parentClass === Group::class) {
            return $this->relatedGroup();
        }

        return $this->relatedGroup()->item($this->parentName);
    }

    /**
     * @throws BuilderNotDefinedException
     * @throws ParentNotDefinedException
     */
    public function relatedGroup(): Group
    {
        if ($this->parentClass === Group::class) {
            return $this->parent();
        }

        return $this->builder()->group($this->parentGroupName);
    }

    /**
     * @throws ParentNotDefinedException
     * @throws BuilderNotDefinedException
     */
    protected function compilerInstance(): Group
    {
        return $this->parent();
    }

    /**
     * @throws BuilderNotDefinedException
     * @throws ParentNotDefinedException
     */
    protected function ensureSubItemExists(string $itemName, array $attributes = [], string|array|\Closure|null $authorize = null): void
    {
        if (!$this->subItems->has($itemName)) {
            $this->subItems->put($itemName, Group::createItemObject($this, $itemName, $attributes, $authorize));
        }
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param string|array|\Closure|null $authorize
     * @return Item
     * @throws BuilderNotDefinedException
     * @throws ParentNotDefinedException
     */
    public function subItem(string $name, array $attributes = [], string|array|\Closure|null $authorize = null): self
    {

        Group::tryItemAttributes($attributes);

        $this->ensureSubItemExists($name, $attributes, $authorize);

        return $this;
    }

    protected function getCompilableItemCollection(): Collection
    {
        return $this->subItems;
    }

    public function removeItem(string $itemName): self
    {
        if ($this->subItems->has($itemName)) {
            $this->subItems->forget($itemName);
        }
        return $this;
    }
}