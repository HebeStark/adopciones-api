<?php

namespace App\Services\AdoptionRequest;

use App\Models\AdoptionRequest;
use App\Enums\AdoptionStatus;
use App\Enums\AnimalStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdoptionRequestStatusService
{
    public function aprobar(AdoptionRequest $solicitud): AdoptionRequest
    {
        if ($solicitud->status !== AdoptionStatus::PENDIENTE) {
            throw ValidationException::withMessages([
                'status' => 'Solo se pueden aprobar solicitudes pendientes.',
            ]);
        }

        return DB::transaction(function () use ($solicitud) {

         $solicitud->update([
                'status' => AdoptionStatus::APROBADA,
            ]);

            $solicitud->animal->update([
                'estado' => AnimalStatus::ADOPTADO,
            ]);

            AdoptionRequest::where('animal_id', $solicitud->animal_id)
                ->where('id', '!=', $solicitud->id)
                ->where('status', AdoptionStatus::PENDIENTE)
                ->update([
                    'status' => AdoptionStatus::RECHAZADA,
                ]);

            return $solicitud->fresh(['animal', 'user']);
        });
    }

     public function rechazar(AdoptionRequest $solicitud): AdoptionRequest
    {
        if ($solicitud->status !== AdoptionStatus::PENDIENTE) {
            throw ValidationException::withMessages([
                'status' => 'Solo se pueden rechazar solicitudes pendientes.',
            ]);
        }

        $solicitud->update([
            'status' => AdoptionStatus::RECHAZADA,
        ]);

        return $solicitud->fresh();
    }

    public function cancelar(AdoptionRequest $solicitud): AdoptionRequest
    {
        if ($solicitud->status !== AdoptionStatus::PENDIENTE) {
            throw ValidationException::withMessages([
                'status' => 'Solo se pueden cancelar solicitudes pendientes.',
            ]);
        }

        $solicitud->update([
            'status' => AdoptionStatus::CANCELADA,
        ]);

        return $solicitud->fresh();
    }
}
