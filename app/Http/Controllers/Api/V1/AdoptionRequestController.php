<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Http\Resources\AdoptionRequestResource;
use App\Services\AdoptionRequest\AdoptionRequestWriteService;
use App\Services\AdoptionRequest\AdoptionRequestStatusService;
use App\Services\AdoptionRequest\AdoptionRequestReadService;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;


class AdoptionRequestController extends Controller
{
    public function __construct(
        protected AdoptionRequestWriteService $writeService,
        protected AdoptionRequestStatusService $statusService,
        protected AdoptionRequestReadService $readService
    ) {}

    #[OA\Get(
        path: "/adoption-requests",
        summary: "List adoption requests",
        description: "Admins can view all adoption requests. Adopters can only view their own requests.",
        tags: ["Adoption Requests"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "status",
                in: "query",
                required: false,
                schema: new OA\Schema(
                    type: "string",
                    enum: ["pending", "approved", "rejected", "cancelled"]
                )
            ),
            new OA\Parameter(
                name: "animal_id",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer")
            ),
            new OA\Parameter(
                name: "per_page",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: 10)
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Paginated list of adoption requests",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    type: "array",
                                    items: new OA\Items(
                                        ref: "#/components/schemas/AdoptionRequest"
                                    )
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )

        ]
    )]

   public function index(Request $request)
    {
        $perPage = $request->query('per_page', 10);

        $filters = $request->only(['status', 'animal_id']);

        $paginator = $this->readService->paginate(
            $request->user(),
            $perPage,
            $filters
        );

        return response()->json([
            'success' => true,
            'data' => AdoptionRequestResource::collection($paginator),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }
   
    #[OA\Post(
        path: "/adoption-requests",
        summary: "Create a new adoption request",
        description: "Creates a new adoption request for the authenticated adopter.",
        tags: ["Adoption Requests"],
        security: [["BearerAuth" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["animal_id"],
                properties: [
                    new OA\Property(property: "animal_id", type: "integer", example: 5)
                ]
            )
        ),
        responses: [
           new OA\Response(
                response: 201,
                description: "Adoption request created successfully",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/AdoptionRequest"
                                )
                            ]
                        )
                    ]
                )
             ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )
        ]
    )]
        
    public function store(Request $request)
    {
        $this->authorize('create', AdoptionRequest::class);

        $request->validate([
            'animal_id' => ['required', 'exists:animals,id'],
        ]);

        $solicitud = $this->writeService->create(
            $request->user(),
            $request->animal_id
        );

         return response()->json([
            'success' => true,
            'data' => new AdoptionRequestResource(
                $solicitud->load(['animal', 'user'])
            ),
            'message' => 'Solicitud creada correctamente.',
        ], 201);
    }

    #[OA\Patch(
        path: "/adoption-requests/{id}/approve",
        summary: "Approve adoption request",
        description: "Only admins can approve a pending adoption request. The related animal will be marked as adopted.",
        tags: ["Adoption Requests"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adoption request approved",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/AdoptionRequest"
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            ),
            new OA\Response(
                response: 404,
                description: "Not found",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )
        ]
    )]

    public function aprobar(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('approve', $adoptionRequest);

        $solicitud = $this->statusService->aprobar($adoptionRequest);

        return response()->json([
            'success' => true,
            'data' => new AdoptionRequestResource(
                $solicitud->load(['animal', 'user'])
            ),
            'message' => 'Solicitud aprobada correctamente.',
        ]);
    }

    #[OA\Patch(
        path: "/adoption-requests/{adoptionRequest}/reject",
        summary: "Reject adoption request",
        description: "Only admins can reject a pending adoption request.",
        tags: ["Adoption Requests"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "adoptionRequest",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adoption request rejected",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/AdoptionRequest"
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )
        ]
    )]

    public function rechazar(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('reject', $adoptionRequest);

        $solicitud = $this->statusService->rechazar($adoptionRequest);

         return response()->json([
            'success' => true,
            'data' => new AdoptionRequestResource(
                $solicitud->load(['animal', 'user'])
            ),
            'message' => 'Solicitud rechazada correctamente.',
        ]);
    }

    #[OA\Patch(
        path: "/adoption-requests/{adoptionRequest}/cancel",
        summary: "Cancel adoption request",
        description: "Allows the owner of the request to cancel it if it is still pending.",
        tags: ["Adoption Requests"],
        security: [["BearerAuth" => []]],
        parameters: [
            new OA\Parameter(
                name: "adoptionRequest",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Adoption request cancelled",
                content: new OA\JsonContent(
                    allOf: [
                        new OA\Schema(ref: "#/components/schemas/SuccessResponse"),
                        new OA\Schema(
                            properties: [
                                new OA\Property(
                                    property: "data",
                                    ref: "#/components/schemas/AdoptionRequest"
                                )
                            ]
                        )
                    ]
                )
            ),
            new OA\Response(
                response: 403,
                description: "Forbidden",
                content: new OA\JsonContent(ref: "#/components/schemas/ErrorResponse")
            )
        ]
    )]

    public function cancelar(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('cancel', $adoptionRequest);

        $solicitud = $this->statusService->cancelar($adoptionRequest);

         return response()->json([
            'success' => true,
            'data' => new AdoptionRequestResource(
                $solicitud->load(['animal', 'user'])
            ),
            'message' => 'Solicitud rechazada correctamente.',
        ]);
    }
}


