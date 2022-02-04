<?php

use Illuminate\Support\Facades\Route;
use Latus\UI\Http\Controllers\WidgetController;

Route::middleware(['web'])->get('/ui/widgets/{widget}/{endpoint}', [WidgetController::class, 'routeToEndpoint'])->name('ui.widgets.endpoint');
Route::middleware(['web'])->get('/ui/widgets/{widget}', [WidgetController::class, 'fetchJson'])->name('ui.widgets');
