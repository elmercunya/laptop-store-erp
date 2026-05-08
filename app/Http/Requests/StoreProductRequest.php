<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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
                'unique:products,name'
            ],

            'category_id' => [
                'required',
                'exists:categories,id'
            ],

            'sale_price' => [
                'required',
                'numeric',
                'min:0'
            ],

            'image' => [
                'nullable',
                'image',
                'max:2048',
                'mimes:jpeg,png,jpg,webp'
            ]
        ];
    }

    public function messages(): array {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.unique' => 'Ya existe otro producto con ese nombre.',
            'category_id.exists' => 'La categoría seleccionada no existe.',
            'sale_price.numeric' => 'El precio debe ser un valor numérico.',
            'sale_price.min' => 'El precio no puede ser negativo.',
            'image.max' => 'La imagen no puede pesar más de 2MB.',
            'image.image' => 'El archivo debe ser una imagen válida.'
        ];
    }
}
