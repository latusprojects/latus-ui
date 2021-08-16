<?php


namespace Latus\UI\Navigation;


use Illuminate\Support\Collection;

class Builder
{
    protected Collection $groups;
    protected Collection $rawItems;
    protected Collection|null $compiledItems;

    public function __construct()
    {
        $this->groups = new Collection();
        $this->rawItems = new Collection();
        $this->compiledItems = null;
    }

    public function putInGroup()
    {
        
    }


}