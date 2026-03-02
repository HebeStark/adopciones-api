<?php

namespace App\Services\Animal;

use App\Models\Animal;

class AnimalDeleteService
{
    public function execute(Animal $animal): void
    {
        $animal->delete();
    }
}