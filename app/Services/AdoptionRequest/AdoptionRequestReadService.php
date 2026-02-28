<?php

namespace App\Services\AdoptionRequest;

use App\Models\AdoptionRequest;
use App\Models\User;
use App\Enums\UserRole;

class AdoptionRequestReadService
{
    public function paginate(User $user, int $perPage = 10, array $filters = [])
    {
        $query = AdoptionRequest::with(['animal', 'user']);

         if ($user->role === UserRole::ADOPTER) {
            $query->where('user_id', $user->id);
        }
        if (!empty($filters['status'])) {
        $query->where('status', $filters['status']);
        }
        if (!empty($filters['animal_id'])) {
        $query->where('animal_id', $filters['animal_id']);
        }


        return $query->latest()->paginate($perPage);
    }
}