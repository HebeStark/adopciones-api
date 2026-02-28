<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\AdoptionRequest;
use App\Enums\AnimalStatus;

class Animal extends Model
{
    use HasFactory;

    protected $table = 'animals';

    protected $fillable = [
        'nombre',
        'tipo',
        'edad',
        'estado',
        'descripcion',
        'foto',
    ];

    protected $casts = [
        'edad' => 'integer',
        'estado' => AnimalStatus::class,
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
}
