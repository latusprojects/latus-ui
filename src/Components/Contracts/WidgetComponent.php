<?php


namespace Latus\UI\Components\Contracts;


use Illuminate\Http\Request;

interface WidgetComponent extends Component
{

    public function context(): ModuleComponent|PageComponent|null;

    public function getDomain(): string|null;

    public function getFullName(): string;

    public function resolvesData(): array|null;

    public function authorizeRequest(Request $request): bool;

}