<?php


namespace Latus\UI\Navigation;


use Illuminate\Support\Collection;
use Illuminate\Support\Traits\Macroable;
use Latus\UI\Navigation\Traits\HasCompilableItems;

class Builder
{
    use Macroable, HasCompilableItems;

    protected Collection $groups;

    public function __construct()
    {
        $this->groups = new Collection();
    }

    protected function ensureGroupExists(string $groupName): void
    {
        if (!$this->groups->has($groupName)) {
            $group = new Group($groupName, $groupName);

            $group->setBuilder($this);
            $this->groups->put($groupName, $group);
        }
    }

    public function group(string $groupName): Group
    {
        $this->ensureGroupExists($groupName);

        return $this->groups->get($groupName);
    }

    public function groups(): Collection
    {
        return $this->groups;
    }

    protected function getCompilableItemCollection(): Collection
    {
        return $this->groups();
    }
}