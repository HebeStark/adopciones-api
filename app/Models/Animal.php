<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory as FactoriesHasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Eloquent\Factories\HasFactory;

class Animal extends Model
{
    use FactoriesHasFactory;

    protected $table = 'animals';

    protected $fillable = [
        'nombre',
        'tipo',
        'edad',
        'descripcion',
        'foto',
    ];
}
