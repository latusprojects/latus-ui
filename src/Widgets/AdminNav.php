<?php


namespace Latus\UI\Widgets;


use Illuminate\Http\Request;
use Illuminate\View\View;
use Latus\UI\Components\WidgetComponent;

class AdminNav extends WidgetComponent
{

    public function resolvesView(): View|null
    {
        return null;
    }

    public function register()
    {

    }

    public function resolvesData(): array|null
    {
        return [];
    }

    public function authorizeRequest(Request $request): bool
    {
        return true;
    }
}