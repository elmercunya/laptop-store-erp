<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateUnitRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !==null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'product_id' => [
                'required',
                'exists:products,id'
            ],

            'serial_number' => [
                'required',
                'string',
                'max:255',
                Rule::unique('units', 'serial_number')->ignore($this->route('unit')),
            ],
        ];
    }

    public function messages():array {
        return [
            'product_id.exists' => 'El producto seleccionado no existe',
            'serial_number.required' => 'El número de serie es obligatorio',
            'serial_number.unique' => 'Ya existe una unidad con ese numero de serie',
            'product_id.required' => 'Debes seleccionar un producto.',
            'serial_number.max' => 'El número de serie no puede superar los 255 caracteres.',
        ];
    }
}
