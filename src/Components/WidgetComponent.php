<?php


namespace Latus\UI\Components;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Latus\UI\Components\Contracts\WidgetComponent as WidgetComponentContract;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Components\Contracts\PageComponent;

abstract class WidgetComponent extends Component implements WidgetComponentContract
{

    public function __construct(
        protected ModuleComponent|PageComponent|null $context = null
    )
    {
    }

    public const WIDGET_ROUTE_PREFIX = '/ui/widgets';

    protected string|null $domain = null;

    protected string $middleware = 'web';

    public function context(): ModuleComponent|PageComponent|null
    {
        return $this->context;
    }

    public function getDomain(): string|null
    {
        return $this->domain;
    }

    public function getFullName(): string
    {
        return ($this->domain ? $this->domain . '-' . $this->name : $this->name);
    }

    public function compose()
    {
    }

    public function endpoint(Request $request, string $endpoint): JsonResponse|null
    {
        return null;
    }
}