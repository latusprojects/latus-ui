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

    protected function ensureGroupExists(string $groupName, string|array|\Closure|null $authorize = null): void
    {
        if (!$this->groups->has($groupName)) {
            $group = new Group($groupName, $groupName, $authorize);

            $group->setBuilder($this);
            $this->groups->put($groupName, $group);
        }
    }

    public function group(string $groupName, string|array|\Closure|null $authorize = null): Group
    {
        $this->ensureGroupExists($groupName, $authorize);

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