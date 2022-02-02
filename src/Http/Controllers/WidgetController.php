<?php

namespace Latus\UI\Http\Controllers;

use Illuminate\Contracts\Container\BindingResolutionException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Latus\UI\Components\Contracts\WidgetComponent;

class WidgetController extends Controller
{
    public function fetchJson(Request $request, string $widget): JsonResponse
    {
        try {
            /**
             * @var WidgetComponent $widgetInstance
             */
            $widgetInstance = app()->make($widget);
        } catch (BindingResolutionException $e) {
            return response()->latusFailed(status: 404, message: 'Widget not found');
        }

        return $widgetInstance->authorizeRequest($request)
            ? response()->latusSuccess(data: ['widget' => $widget, 'data' => $widgetInstance->resolvesData() ?? []])
            : response()->latusFailed(status: 403, message: 'Forbidden');
    }

    public function routeToEndpoint(Request $request, string $widget, string $endpoint): JsonResponse
    {
        try {
            /**
             * @var WidgetComponent $widgetInstance
             */
            $widgetInstance = app()->make($widget);
        } catch (BindingResolutionException $e) {
            return response()->latusFailed(status: 404, message: 'Widget not found');
        }

        if (!$widgetInstance->authorizeRequest($request)) {
            return response()->latusFailed(status: 403, message: 'Forbidden');
        }

        return $widgetInstance->endpoint($request, $endpoint) ?? response()->latusSuccess(status: 204, message: 'No Content');
    }
}