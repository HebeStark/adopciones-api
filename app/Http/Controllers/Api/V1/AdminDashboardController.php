<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\Request;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService
    ) {}

    public function __invoke(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getStats(),
        ]);
    }
}
