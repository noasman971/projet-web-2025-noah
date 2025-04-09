<?php

namespace App\Http\Requests;
use Illuminate\Foundation\Http\FormRequest;

class CommonCreateTaskRequest extends FormRequest
{


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'Nom obligatoire',
            'description.required' => 'Description obligatoire',
            'name.max'=>'Le nom a pour maximum 255 caractères',
            'description.max' => 'La description a pour maximum 255 caractères',
        ];
    }
}
