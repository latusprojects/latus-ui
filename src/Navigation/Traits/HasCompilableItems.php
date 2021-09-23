<?php

namespace Latus\UI\Navigation\Traits;

use Illuminate\Support\Collection;
use Latus\UI\Navigation\Contracts\BuilderProvider;

trait HasCompilableItems
{

    protected array $beforeItems = [];
    protected array $afterItems = [];
    protected bool $ignoresAuthorization = false;
    protected array $itemReferences = [];

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

    public function compileItems(bool $ignoreAuthorization = false, bool $force = false): Collection
    {
        if (!$force && isset($this->{'compiledItems'})) {
            return $this->compiledItems;
        }

        $this->ignoresAuthorization = $ignoreAuthorization;

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
                if (($beforeItem = $rawItems->get($beforeItemName))) {
                    $this->fetchBeforeAndAfterItems($beforeItem, $rawItems, $tempCollection);
                }
            }
        }

        if ($this->ignoresAuthorization || $item->authorized()) {
            $tempCollection->put($item->getName(), $item);
            $this->itemReferences[] = $item->getName();
        }

        if (isset($this->afterItems[$item->getName()])) {
            foreach ($this->afterItems[$item->getName()] as $afterItemName) {
                if (($afterItem = $rawItems->get($afterItemName))) {
                    $this->fetchBeforeAndAfterItems($afterItem, $rawItems, $tempCollection);
                }
            }
        }

        return $tempCollection;
    }

    public function getItemReferences(): array
    {
        return $this->itemReferences;
    }

    abstract protected function getCompilableItemCollection(): Collection;
}