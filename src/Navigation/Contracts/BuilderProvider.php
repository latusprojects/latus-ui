<?php

namespace Latus\UI\Navigation\Contracts;

use Latus\UI\Navigation\Builder;
use Latus\UI\Navigation\Group;

interface BuilderProvider
{
    public function group(string $groupName): Group;

    public function setBuilder(Builder &$builder): void;

    public function &builder(): Builder|null;

    public function authorized(): bool;
}