<?php

namespace Latus\UI\Navigation\Traits;

use Latus\Permissions\Models\User;
use Latus\Permissions\Services\UserService;

trait SupportsAuthorization
{
    protected UserService $userService;
    protected string|array|\Closure|null $authorize = null;

    protected function getUserService(): UserService
    {
        if (!isset($this->{'userService'})) {
            $this->userService = app((UserService::class));
        }

        return $this->userService;
    }

    public function requireAuthorization(string|array|\Closure $authorize): self
    {
        $this->authorize = $authorize;

        return $this;
    }

    public function authorized(): bool
    {
        if (!$this->authorize) {
            return true;
        }

        if ($this->authorize instanceof \Closure) {
            return app()->call($this->authorize, ['item' => $this]);
        }

        /**
         * @var User $user
         */
        $user = auth()->user();

        if (is_string($this->authorize)) {
            return $this->getUserService()->userHasPermissionByString($user, $this->authorize);
        }

        foreach ($this->authorize as $permissionString) {
            if (!$this->getUserService()->userHasPermissionByString($user, $permissionString)) {
                return false;
            }
        }
        return true;
    }
}