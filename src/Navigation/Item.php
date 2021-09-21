<?php

namespace Latus\UI\Navigation;

use Latus\UI\Exceptions\BuilderNotDefinedException;
use Latus\UI\Exceptions\GroupNotDefinedException;
use Latus\UI\Navigation\Contracts\BuilderProvider;
use Latus\UI\Navigation\Traits\PrependsAndAppendsItems;
use Latus\UI\Navigation\Traits\ProvidesBuilder;
use Latus\UI\Navigation\Traits\SupportsAuthorization;

class Item implements BuilderProvider
{
    use ProvidesBuilder, PrependsAndAppendsItems, SupportsAuthorization;

    protected string $groupName;

    public function __construct(
        protected string                     $name,
        protected string                     $label,
        protected string                     $icon,
        protected string                     $url,
        protected string|null                $view,
        protected string|array|\Closure|null $authorize = null
    )
    {
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
     * @throws GroupNotDefinedException
     * @throws BuilderNotDefinedException
     */
    public function remove(): Group
    {
        return $this->parent()->removeItem($this->getName());
    }

    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @throws BuilderNotDefinedException
     */
    public function setGroup(Group &$group): void
    {
        $this->groupName = $group->getName();
        $this->setBuilder($group->builder());
    }

    /**
     * @throws GroupNotDefinedException
     * @throws BuilderNotDefinedException
     */
    public function parent(): Group
    {
        if (!isset($this->{'groupName'})) {
            throw new GroupNotDefinedException('No parent-group was defined but is required. Items must always have a reference of a parent-group-instance.');
        }
        return $this->builder()->group($this->groupName);
    }

    /**
     * @throws GroupNotDefinedException
     * @throws BuilderNotDefinedException
     */
    protected function compilerInstance(): Group
    {
        return $this->parent();
    }
}