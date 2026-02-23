<?php
namespace App\Services\Animal;

use App\Models\Animal;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AnimalReadService
{
    public function paginate(int $perPage = 10): LengthAwarePaginator
    {
        return Animal::query()
        ->orderBy('created_at', 'desc')
        ->paginate($perPage);
    }

}