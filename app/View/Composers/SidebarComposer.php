<?php

namespace App\View\Composers;

use Illuminate\View\View;
use App\Services\SidebarBadgeService;

class SidebarComposer
{
    public function compose(View $view): void
    {
        $view->with('sidebarBadges', SidebarBadgeService::getCounts());
    }
}
