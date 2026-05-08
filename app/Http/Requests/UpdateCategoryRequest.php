<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->isAdmin() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'max:255',
                Rule::unique('categories', 'name')->ignore($this->route('category'))
            ]
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'El nombre de la categoría es obligatorio.',
            'name.unique' => 'Ya existe una categoría con ese nombre.',
        ];
    }
}
