<?php

namespace App\Services\Animal;

use App\Models\Animal;

class AnimalUpdateService
{
    public function execute(Animal $animal, array $data): Animal
    {
        $animal->update($data);

        return $animal->fresh();
    }
}