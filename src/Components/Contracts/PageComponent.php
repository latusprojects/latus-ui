<?php


namespace Latus\UI\Components\Contracts;


interface PageComponent extends Component
{
    
    public function module(): ModuleComponent;
}