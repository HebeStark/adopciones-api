<?php

namespace App\Services\AdoptionRequest;

use App\Models\AdoptionRequest;
use App\Models\Animal;
use App\Models\User;
use App\Enums\AdoptionStatus;
use App\Enums\AnimalStatus;
use Illuminate\Validation\ValidationException;

class AdoptionRequestWriteService
{
    public function create(User $user, int $animalId): AdoptionRequest
    {
        $animal = Animal::findOrFail($animalId);

        if ($animal->estado !== AnimalStatus::DISPONIBLE) {
            throw ValidationException::withMessages([
                'animal_id' => 'El animal no está disponible para adopción.',
            ]);
        }

         $alreadyPending = AdoptionRequest::where('user_id', $user->id)
            ->where('animal_id', $animal->id)
            ->where('status', AdoptionStatus::PENDIENTE)
            ->exists();

        if ($alreadyPending) {
            throw ValidationException::withMessages([
                'animal_id' => 'Ya tienes una solicitud pendiente para este animal.',
            ]);
        }

         $alreadyApproved = AdoptionRequest::where('animal_id', $animal->id)
            ->where('status', AdoptionStatus::APROBADA)
            ->exists();

        if ($alreadyApproved) {
            throw ValidationException::withMessages([
                'animal_id' => 'Este animal ya ha sido adoptado.',
            ]);
        }

         return AdoptionRequest::create([
            'user_id' => $user->id,
            'animal_id' => $animal->id,
            'status' => AdoptionStatus::PENDIENTE,
        ]);
    }
}