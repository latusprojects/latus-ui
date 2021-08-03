<?php


namespace Latus\UI\Components\Contracts;


interface ModuleComponent extends Component
{
    public function definePages(array $pages);

    public function getPage(string $name): PageComponent;
}