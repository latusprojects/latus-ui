<?php


namespace Latus\UI\Navigation;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;

class Group
{
    protected Collection $items;
    protected Collection $itemViews;
    protected Collection $queuedItems;

    public const ITEM_VALIDATION_RULES = [
        'label' => 'sometimes|string|min:3|max:255',
        'icon' => 'sometimes|string|min:1|max:255',
        'url' => 'sometimes|string|min:3|max:255'
    ];

    public function __construct(
        protected string      $name,
        protected string      $label,
        protected string|null $url = null)
    {
        $this->items = new Collection();
        $this->itemViews = new Collection();
        $this->queuedItems = new Collection();
    }

    public function removeItem(string $name): self
    {
        if ($this->items->has($name)) {
            $this->items->forget($name);
        }

        if ($this->itemViews->has($name)) {
            $this->itemViews->forget($name);
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

    public function putItemBefore(string $item, array $attributes, string $before, array|\Closure $view = null): self
    {

        $this->tryItemAttributes($attributes);

        if ($view) {
            $this->itemViews->put($item, $view);
        }


        if ($this->items->has($before)) {

            $this->queuedItems->

            $this->items->putBefore($item, $attributes, $before);
        } else {
            if (!$this->queuedItems->has($before)) {
                $this->queuedItems->put($before, ['before' => [], 'after' => []]);
            }

            $this->queuedItems->get($before);

        }


        return $this;
    }

    /**
     * @param string $name
     * @param array $attributes
     * @param array|\Closure|null $view
     */
    public function putItem(string $name, array $attributes, array|\Closure $view = null): self
    {

        $this->tryItemAttributes($attributes);

        if ($view) {
            $this->itemViews->put($name, $view);
        }

        $this->items->put($name, $attributes);

        return $this;

    }

}