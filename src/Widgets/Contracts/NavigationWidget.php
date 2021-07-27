<?php


namespace Latus\UI\Widgets\Contracts;


use Illuminate\Support\Collection;

interface NavigationWidget
{

    public function &getItems(): Collection;

    public function validateItems();

    public function levels(): int;

    public function supportsPermissions(): bool;
}