<?php

namespace App\Policies;

use App\Models\AdoptionRequest;
use App\Models\User;
use App\Enums\UserRole;
use App\Enums\AdoptionStatus;

class AdoptionRequestPolicy
{
    public function viewAny(User $user): bool
    {
       return in_array($user->role, [
            UserRole::ADMIN,
            UserRole::ADOPTER,
        ]);
    }

    public function view(User $user, AdoptionRequest $adoptionRequest): bool
    {
        if ($user->role === UserRole::ADMIN) {
            return true;
        }

        return $user->id === $adoptionRequest->user_id;
    }

    public function create(User $user): bool
    {
        return $user->role === UserRole::ADOPTER;
    }

    public function approve(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->role === UserRole::ADMIN;
    
    }

    public function reject(User $user, AdoptionRequest $adoptionRequest): bool
    {
        return $user->role === UserRole::ADMIN;
       
    }

    public function cancel(User $user, AdoptionRequest $adoptionRequest): bool
    {
        if ($user->role !== UserRole::ADOPTER) {
            return false;
        }

        return $user->id === $adoptionRequest->user_id
            && $adoptionRequest->status === AdoptionStatus::PENDIENTE;
    }
    
}