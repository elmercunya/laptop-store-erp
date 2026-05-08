<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreClientRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user() !== null;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $rules = [
            'document_type' => [
                'required',
                'in:DNI,RUC,CE',
                'string'
            ],

            'document_number' => [
                'required',
                'unique:clients,document_number',
                'string'
            ],

            'name' => [
                'required',
                'max:255',
                'string'
            ]
        ];

        if($this->input('document_type')=== 'DNI') {
            $rules['document_number'][] = 'digits:8';
        }elseif($this->input('document_type')=== 'RUC') {
            $rules['document_number'][] = 'digits:11|starts_with:10,20';
        }elseif($this->input('document_type')=== 'CE') {
            $rules['document_number'][] = 'max:20';
        }

        return $rules;

    }

    public function messages():array {
        return [
            'document_type.in' => 'El tipo de documento debe ser DNI, RUC o CE.',
            'document_number.required' => 'El número de documento es obligatorio.',
            'document_number.unique' => 'Ya existe un cliente con ese número de documento.',
            'document_number.numeric' => 'El número de documento solo debe contener dígitos.',
            'document_number.digits' => 'El DNI debe tener exactamente 8 dígitos.',
            'document_number.starts_with' => 'El RUC debe comenzar con 10 o 20.',
            'name.required' => 'El nombre del cliente es obligatorio.',
            'name.max' => 'El nombre no puede superar los 255 caracteres.',
        ];
    }
}
