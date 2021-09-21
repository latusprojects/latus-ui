<?php


namespace Latus\UI\Widgets\Contracts;


use Illuminate\Support\Collection;

interface NavigationWidget
{
    public function setReference(string $reference);

    public function getReference(): string|null;
}