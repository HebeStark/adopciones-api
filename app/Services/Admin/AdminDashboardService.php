<?php

namespace App\Services\Admin;

use App\Models\Animal;
use App\Models\AdoptionRequest;
use App\Enums\AnimalStatus;
use App\Enums\AdoptionStatus;

class AdminDashboardService
{
    public function getStats(): array
    {
        return [
            'animals' => [
                'total' => Animal::count(),
                'available' => Animal::where('estado', AnimalStatus::DISPONIBLE)->count(),
                'adopted' => Animal::where('estado', AnimalStatus::ADOPTADO)->count(),
            ],
            'adoption_requests' => [
                'pending' => AdoptionRequest::where('status', AdoptionStatus::PENDIENTE)->count(),
                'approved' => AdoptionRequest::where('status', AdoptionStatus::APROBADA)->count(),
            ],
        ];
    }
}