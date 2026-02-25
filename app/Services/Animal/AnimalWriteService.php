<?php

namespace App\Services\Animal;

use App\Models\Animal;

class AnimalWriteService
{
    public function create(array $data): Animal
    {
        if (!isset($data['estado'])) {
        $data['estado'] = 'disponible';
    }
        return Animal::create($data);
    }
}