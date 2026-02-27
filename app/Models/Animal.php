<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory as FactoriesHasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Eloquent\Factories\HasFactory;
use App\Models\AdoptionRequest;

class Animal extends Model
{
    use FactoriesHasFactory;

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
    ];

    public function adoptionRequests()
    {
        return $this->hasMany(AdoptionRequest::class);
    }
}
