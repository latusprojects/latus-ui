<?php


namespace Latus\UI\Navigation;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Latus\UI\Exceptions\BuilderNotDefinedException;
use Latus\UI\Navigation\Contracts\BuilderProvider;
use Latus\UI\Navigation\Traits\HasCompilableItems;
use Latus\UI\Navigation\Traits\PrependsAndAppendsItems;
use Latus\UI\Navigation\Traits\ProvidesBuilder;
use Latus\UI\Navigation\Traits\SupportsAuthorization;

class Group implements BuilderProvider
{
    use ProvidesBuilder, HasCompilableItems, PrependsAndAppendsItems, SupportsAuthorization;

    protected Collection $items;

    public const ITEM_VALIDATION_RULES = [
        'label' => 'sometimes|string|min:3|max:255',
        'icon' => 'sometimes|string|min:1|max:255',
        'url' => 'sometimes|string|min:3|max:255',
        'view' => 'sometimes|string|min:1|max:255'
    ];

    public function __construct(
        protected string                     $name,
        protected string                     $label,
        protected string|null                $icon = null,
        protected string|null                $url = null,
        protected string|array|\Closure|null $authorize = null,
    )
    {
        $this->items = new Collection();
    }

    /**
     * @throws BuilderNotDefinedException
     */
    protected function ensureItemExists(string $itemName, array $attributes = [], string|array|\Closure|null $authorize = null): void
    {
        if (!$this->items->has($itemName)) {
            $label = $attributes['label'] ?? $itemName;
            $icon = $attributes['icon'] ?? '';
            $url = $attributes['url'] ?? '';
            $view = $attributes['view'] ?? null;

            $item = new Item($itemName, $label, $icon, $url, $view, $authorize);
            $item->setGroup($this);

            $this->items->put($itemName, $item);
        }
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getLabel(): string
    {
        return $this->label;
    }

    public function setLabel(string $label): self
    {
        $this->label = $label;
        return $this;
    }

    public function getIcon(): string|null
    {
        return $this->icon;
    }

    public function setIcon(string $icon): self
    {
        $this->icon = $icon;
        return $this;
    }

    public function getUrl(string $url): string|null
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;
        return $this;
    }

    public function removeItem(string $itemName): self
    {
        if ($this->items->has($itemName)) {
            $this->items->forget($itemName);
        }
        return $this;
    }

    protected function tryItemAttributes(array $attributes)
    {
        $validator = Validator::make($attributes, self::ITEM_VALIDATION_RULES);

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }
    }


    /**
     * @param string $name
     * @param array $attributes
     * @return Group
     * @throws BuilderNotDefinedException
     */
    public function item(string $name, array $attributes = [], string|array|\Closure|null $authorize = null): self
    {

        $this->tryItemAttributes($attributes);

        $this->ensureItemExists($name, $attributes, $authorize);

        return $this;

    }

    protected function getCompilableItemCollection(): Collection
    {
        return $this->items;
    }

    /**
     * @throws BuilderNotDefinedException
     */
    protected function compilerInstance(): ?Builder
    {
        return $this->builder();
    }
}