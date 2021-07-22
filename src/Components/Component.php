<?php


namespace Latus\UI\Components;

use \Latus\UI\Components\Contracts\Component as ComponentContract;

abstract class Component implements ComponentContract
{
    protected string $name;

    public function getName(): string
    {
        return $this->name;
    }
}