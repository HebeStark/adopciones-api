<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class AnimalUpdateRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // luego podemos endurecer con policies
    }

    public function rules(): array
    {
        $animalId = $this->route('animal')->id;

        return [
            'nombre' => [
                'required',
                'string',
                'max:255',
                Rule::unique('animals', 'nombre')->ignore($animalId),
            ],
            'tipo' => [
                'required',
                Rule::in(['perro', 'gato']),
            ],
            'edad' => [
                'required',
                'integer',
                'min:0',
            ],
            'estado' => [
                'required',
                Rule::in(['disponible', 'adoptado']),
            ],
            'descripcion' => [
                'nullable',
                'string',
            ],
            'foto' => [
                'nullable',
                'string',
                'max:255',
            ],
        ];
    }
}