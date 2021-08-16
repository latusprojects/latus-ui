<?php

namespace Latus\UI\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Latus\UI\Widgets\AdminNav;

class AdminNavDefined
{
    use Dispatchable;

    public function __construct(
        public AdminNav &$adminNav
    )
    {
    }
}