<?php


namespace Latus\UI\Widgets;


use Illuminate\Http\Request;
use Latus\Permissions\Models\User;
use Latus\Permissions\Services\PermissionService;
use Latus\Permissions\Services\UserService;
use Latus\UI\Components\Contracts\ModuleComponent;
use Latus\UI\Components\Contracts\PageComponent;

class AdminNav extends NavigationWidget
{


    public const MASTER_PERMISSION = 'module.admin';

    protected UserService $userService;
    protected PermissionService $permissionService;

    public function __construct(PageComponent|ModuleComponent|null $context = null)
    {
        parent::__construct($context);

        $this->userService = app()->make(UserService::class);
        $this->permissionService = app()->make(PermissionService::class);
    }

    public function authorizeRequest(Request $request): bool
    {
        /**
         * @var User $user
         */
        $user = auth()->user();

        return $this->userService->userHasPermissionByString($user, self::MASTER_PERMISSION);
    }
}