<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AnimalIndexRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'per_page' => [
            'sometimes|integer|min:1|max:100'],
        ];
    }


    protected function prepareForValidation(): void
    {
        $this->merge([
            'per_page' => $this->query('per_page', 20),
        ]);
    }

}

