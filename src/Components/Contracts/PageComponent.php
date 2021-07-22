<?php


namespace Latus\UI\Components\Contracts;


use Illuminate\Support\Collection;

interface PageComponent extends Component
{

    public function module(): ModuleComponent;

    public function compose();

    public function getSettings(): Collection;

    public function getContent(): mixed;

    public function setContent(mixed $content);
}