<?php


namespace Latus\UI\Components\Contracts;


use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

interface WidgetComponent extends Component
{

    public function context(): ModuleComponent|PageComponent|null;

    public function getDomain(): string|null;

    public function getFullName(): string;

    public function resolvesData(): Collection|array|null;

    public function endpoint(Request $request, string $endpoint): JsonResponse|null;

    public function authorizeRequest(Request $request): bool;

}