<?php


namespace Latus\UI\Widgets;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Latus\UI\Components\WidgetComponent;

abstract class NavigationWidget extends WidgetComponent implements Contracts\NavigationWidget
{

    protected Collection $items;

    public static function getItemValidationRules(): array
    {
        return [
            '*.name' => 'required|string|min:3|max:255',
            '*.label' => 'required|string|min:3|max:255',
            '*.icon' => 'sometimes|string|min:2|max:255',
            '*.url' => 'sometimes|url',
            '*.permissions' => 'sometimes|array',
            '*.permissions.*' => 'string|exists:permissions,name',
            '*.items' => [
                'sometimes',
                'array',
                function ($attribute, $value, $fail) {
                    $subValidator = Validator::make($value, self::getItemValidationRules());
                    if ($subValidator->fails()) {
                        $fail($subValidator->errors()->first());
                    }
                }
            ]
        ];
    }

    public function &getItems(): Collection
    {
        if (!$this->items) {
            $this->items = new Collection();
        }
        return $this->items;
    }

    public function validateItems()
    {
        $validator = Validator::make($this->items->toArray(), self::getItemValidationRules());

        if ($validator->fails()) {
            throw new \InvalidArgumentException($validator->errors()->first());
        }
    }

    public function resolvesView(): View|null
    {
        return null;
    }

    public function resolvesData(): array|null
    {
        return $this->getItems()->toArray();
    }

    public function register()
    {
    }
}