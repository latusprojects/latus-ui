<?php

namespace Latus\UI\Navigation\Traits;

use Latus\UI\Exceptions\BuilderNotDefinedException;
use Latus\UI\Exceptions\ParentNotDefinedException;

trait PrependsAndAppendsItems
{
    /**
     * @throws ParentNotDefinedException
     * @throws BuilderNotDefinedException
     */
    public function prepend(string $itemName): self
    {
        $this->compilerInstance()->prependItem($this->getName(), $itemName);
        return $this;
    }

    /**
     * @throws ParentNotDefinedException
     * @throws BuilderNotDefinedException
     */
    public function append(string $itemName): self
    {
        $this->compilerInstance()->appendItem($this->getName(), $itemName);
        return $this;
    }

    protected abstract function compilerInstance();
}