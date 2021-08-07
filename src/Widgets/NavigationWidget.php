<?php


namespace Latus\UI\Widgets;


use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Validator;
use Illuminate\View\View;
use Latus\Permissions\Models\Permission;
use Latus\Permissions\Models\User;
use Latus\Permissions\Services\PermissionService;
use Latus\Permissions\Services\UserService;
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
            '*.group' => 'sometimes|string|min:3',
            '*.permissions' => 'sometimes|array',
            '*.permissions.*' => 'string|exists:permissions,name',
            '*.items' => [
                'sometimes',
                function ($attribute, $value, $fail) {
                    if (!($value instanceof Collection || is_array($value))) {
                        $fail('The ' . $attribute . ' must be either an array or an instance of Illuminate\Support\Collection');
                    }
                    $arrayItems = $value;

                    if ($value instanceof Collection) {
                        $arrayItems = $value->toArray();
                    }
                    $subValidator = Validator::make($arrayItems, self::getItemValidationRules());
                    if ($subValidator->fails()) {
                        $fail($subValidator->errors()->first());
                    }
                }
            ]
        ];
    }

    public function &getItems(): Collection
    {
        if (!isset($this->{'items'})) {
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

    protected function userHasPermissions(PermissionService $permissionService, UserService $userService, User $user, array $permissions): bool
    {
        foreach ($permissions as $permissionName) {
            /**
             * @var Permission $permission
             */
            $permission = $permissionService->findByName($permissionName);
            if (!$userService->userHasPermission($user, $permission)) {
                return false;
            }
        }

        return true;
    }

    public function resolvesData(): array|null
    {
        $finalItems = [];

        /**
         * @var PermissionService $permissionService
         */
        $permissionService = app()->make(PermissionService::class);

        /**
         * @var UserService $userService
         */
        $userService = app()->make(UserService::class);

        /**
         * @var User $user
         */
        $user = auth()->user();

        foreach ($this->getItems()->toArray() as $item) {
            if (
                !$this->supportsPermissions()
                || !isset($item['permissions'])
                || $this->userHasPermissions($permissionService, $userService, $user, $item['permissions'])
            ) {
                $finalItems[] = $item;
            }
        }
        return $finalItems;
    }

    public function register()
    {
    }

    public function putBefore(string $indexKey, array $items)
    {
        $targetItem = $this->getItems()->where('name', $indexKey)->first();

        if (!$targetItem) {
            return;
        }
        if (isset($targetItem['group'])) {
            foreach ($items as &$item) {
                $item['group'] = $targetItem['group'];
            }
        }

        $this->getItems()->putBefore($indexKey, $items);

    }

    public function putAfter(string $indexKey, array $items)
    {
        $targetItem = $this->getItems()->where('name', $indexKey)->first();

        if (!$targetItem) {
            return;
        }
        if (isset($targetItem['group'])) {
            foreach ($items as &$item) {
                $item['group'] = $targetItem['group'];
            }
        }

        $this->getItems()->putAfter($indexKey, $items);
    }
}