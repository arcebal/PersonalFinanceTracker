<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\SidebarBadgeService;
use Illuminate\Http\JsonResponse;

class SidebarBadgeController extends Controller
{
    public function __invoke(): JsonResponse
    {
        return response()->json(SidebarBadgeService::getCounts());
    }
}
