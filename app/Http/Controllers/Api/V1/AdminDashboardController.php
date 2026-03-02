<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\Admin\AdminDashboardService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

class AdminDashboardController extends Controller
{
    public function __construct(
        protected AdminDashboardService $dashboardService
    ) {}

    #[OA\Get(
        path: "/admin/dashboard",
        summary: "Admin dashboard statistics",
        description: "Returns statistics about animals and adoption requests. Only accessible by admin users.",
        tags: ["Dashboard"],
        security: [["BearerAuth" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Dashboard data retrieved successfully",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/SuccessResponse"
                )
            ),
              new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/ErrorResponse"
                )
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden",
                content: new OA\JsonContent(
                    ref: "#/components/schemas/ErrorResponse"
                )
            )
        ]
    )]

    public function __invoke(Request $request)
    {
        return response()->json([
            'success' => true,
            'data' => $this->dashboardService->getStats(),
        ]);
    }
}
