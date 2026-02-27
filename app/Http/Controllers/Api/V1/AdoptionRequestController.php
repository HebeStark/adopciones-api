<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\AdoptionRequest;
use App\Services\AdoptionRequest\AdoptionRequestWriteService;
use App\Services\AdoptionRequest\AdoptionRequestStatusService;
use Illuminate\Http\Request;

class AdoptionRequestController extends Controller
{
    public function __construct(
        protected AdoptionRequestWriteService $writeService,
        protected AdoptionRequestStatusService $statusService
    ) {}

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
            'data' => $solicitud,
            'message' => 'Solicitud creada correctamente.',
        ], 201);
    }

    public function aprobar(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('approve', $adoptionRequest);

        $solicitud = $this->statusService->aprobar($adoptionRequest);

        return response()->json([
            'success' => true,
            'data' => $solicitud,
            'message' => 'Solicitud aprobada correctamente.',
        ]);
    }

    public function rechazar(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('reject', $adoptionRequest);

        $solicitud = $this->statusService->rechazar($adoptionRequest);

        return response()->json([
            'success' => true,
            'data' => $solicitud,
            'message' => 'Solicitud rechazada correctamente.',
        ]);
    }

    public function cancelar(AdoptionRequest $adoptionRequest)
    {
        $this->authorize('cancel', $adoptionRequest);

        $solicitud = $this->statusService->cancelar($adoptionRequest);

        return response()->json([
            'success' => true,
            'data' => $solicitud,
            'message' => 'Solicitud cancelada correctamente.',
        ]);
    }
}


