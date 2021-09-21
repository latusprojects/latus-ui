<?php

namespace Latus\UI\Navigation\Traits;

use Latus\UI\Exceptions\BuilderNotDefinedException;
use Latus\UI\Navigation\Builder;
use Latus\UI\Navigation\Group;

trait ProvidesBuilder
{
    protected Builder $builder;

    public function group(string $groupName): Group
    {
        return $this->builder->group($groupName);
    }

    public function setBuilder(Builder &$builder): void
    {
        $this->builder = $builder;
    }

    /**
     * @return Builder|null
     * @throws BuilderNotDefinedException
     */
    public function &builder(): Builder|null
    {
        if (!isset($this->{'builder'})) {
            throw new BuilderNotDefinedException('No builder was defined but is required. Classes implementing "BuilderProvider" must always have a reference of a builder-instance.');
        }

        return $this->builder;
    }
}