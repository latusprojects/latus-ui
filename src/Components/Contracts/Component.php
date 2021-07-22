<?php


namespace Latus\UI\Components\Contracts;


use Illuminate\View\View;

interface Component
{
    public function resolvesView(): View|null;

    public function register();

    public function compose();

    public function getName(): string;
}