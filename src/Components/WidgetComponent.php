<?php


namespace Latus\UI\Components;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Latus\UI\Components\Contracts\WidgetComponent as WidgetComponentContract;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Components\Contracts\PageComponent;

abstract class WidgetComponent extends Component implements WidgetComponentContract
{

    public function __construct(
        protected ModuleComponent|PageComponent|null &$context = null
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
        $route = self::WIDGET_ROUTE_PREFIX . '/' . $this->getFullName();

        $class = static::class;

        app()->bind($class, $this);

        Route::middleware($this->middleware)->get($route, function (Request $request) use ($class) {
            /**
             * @var WidgetComponentContract $widget
             */
            $widget = app()->make($class);

            $resolved_data = $widget->resolvesData();

            if (!$this->authorizeRequest($request)) {
                return response('Forbidden', 403, ['Content-Type', 'application/json']);
            }

            if (!$resolved_data) {
                return response('No Content', 204, ['Content-Type', 'application/json']);
            }

            return response([
                'status' => 200,
                'name' => $widget->getName(),
                'domain' => $widget->getDomain(),
                'data' => $resolved_data
            ]);
        });
    }
}