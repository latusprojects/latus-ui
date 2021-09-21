<?php

namespace Latus\UI\Navigation\Traits;

use Illuminate\Support\Collection;
use Latus\UI\Navigation\Contracts\BuilderProvider;

trait HasCompilableItems
{

    protected array $beforeItems = [];
    protected array $afterItems = [];

    protected Collection $compiledItems;

    protected function isItemAlreadyBeforeOrAfterItem(string $currentItemName, string $itemName): bool
    {
        return
            (isset($this->beforeItems[$itemName]) && in_array($currentItemName, $this->beforeItems[$itemName])) ||
            (isset($this->afterItems[$itemName]) && in_array($currentItemName, $this->afterItems[$itemName]));
    }

    public function prependItem(string $currentItemName, string $itemName): self
    {
        if ($this->isItemAlreadyBeforeOrAfterItem($currentItemName, $itemName)) {
            return $this;
        }

        if (!isset($this->beforeItems[$currentItemName])) {
            $this->beforeItems[$currentItemName] = [];
        }

        if (!in_array($itemName, $this->beforeItems[$currentItemName])) {
            $this->beforeItems[$currentItemName][] = $itemName;
        }

        return $this;
    }

    public function appendItem(string $currentItemName, string $itemName): self
    {
        if ($this->isItemAlreadyBeforeOrAfterItem($currentItemName, $itemName)) {
            return $this;
        }

        if (!isset($this->afterItems[$currentItemName])) {
            $this->afterItems[$currentItemName] = [];
        }

        if (!in_array($itemName, $this->afterItems[$currentItemName])) {
            $this->afterItems[$currentItemName][] = $itemName;
        }

        return $this;
    }

    public function compileItems(bool $force = false): Collection
    {
        if (!$force && isset($this->{'compiledItems'})) {
            return $this->compiledItems;
        }

        $rawItems = clone $this->getCompilableItemCollection();

        $compiledItems = new Collection();

        /**
         * @var BuilderProvider $item
         */
        foreach ($rawItems as $item) {
            $tempCollection = $this->fetchBeforeAndAfterItems($item, $rawItems);
            foreach ($tempCollection as $compiledKey => $compiledItem) {
                $compiledItems->put($compiledKey, $compiledItem);
                $rawItems->forget($compiledKey);
            }
        }

        return $compiledItems;
    }

    protected function fetchBeforeAndAfterItems(BuilderProvider $item, Collection &$rawItems, Collection $tempCollection = null): Collection
    {
        if (!$tempCollection) {
            $tempCollection = new Collection();
        }

        if (isset($this->beforeItems[$item->getName()])) {
            foreach ($this->beforeItems[$item->getName()] as $beforeItemName) {
                $this->fetchBeforeAndAfterItems($rawItems->get($beforeItemName), $rawItems, $tempCollection);
            }
        }

        $tempCollection->put($item->getName(), $item);

        if (isset($this->afterItems[$item->getName()])) {
            foreach ($this->afterItems[$item->getName()] as $afterItemName) {
                $this->fetchBeforeAndAfterItems($rawItems->get($afterItemName), $rawItems, $tempCollection);
            }
        }

        return $tempCollection;
    }

    abstract protected function getCompilableItemCollection(): Collection;
}