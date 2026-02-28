<?php

namespace App\Services\AdoptionRequest;

use App\Models\AdoptionRequest;
use App\Models\User;
use App\Enums\UserRole;

class AdoptionRequestReadService
{
    public function paginate(User $user, int $perPage = 10)
    {
        $query = AdoptionRequest::with(['animal', 'user']);

         if ($user->role === UserRole::ADOPTER) {
            $query->where('user_id', $user->id);
        }

        return $query->latest()->paginate($perPage);
    }
}