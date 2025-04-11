<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class KnowledgeCreateRequest extends FormRequest
{
//    /**
//     * Determine if the user is authorized to make this request.
//     */
//    public function authorize(): bool
//    {
//        return false;
//    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'langage' => 'required|string|max:255',
            'number' => 'required|integer|min:1|max:30',
        ];
    }

    public function messages(): array
    {
        return [
            'langage.required' => 'Le champ langage est requis.',
            'langage.string' => 'Le champ langage doit être une chaîne de caractères.',
            'langage.max' => 'Le champ langage ne doit pas dépasser 255 caractères.',
            'number.required' => 'Le champ nombre est requis.',
            'number.integer' => 'Le champ nombre doit être un entier.',
            'number.min' => 'Le champ nombre doit être supérieur ou égal à 1.',
            'number.max' => 'Le champ nombre ne doit pas dépasser 30.',
        ];
    }
}
