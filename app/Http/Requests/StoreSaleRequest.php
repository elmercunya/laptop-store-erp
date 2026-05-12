<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;


class StoreSaleRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return $this->user()?->canSell() ?? false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'prices' => [
                'required',
                'array',
                'min:1'
            ],

            'prices.*' => [
                'required',
                'numeric',
                'min:0'
            ],

            'unit_ids' => [
                'required',
                'array',
                'min:1'
            ],

            'unit_ids.*' => [
                'required',
                'exists:units,id'
            ],

            'voucher' => [
                'required',
                'in:nota de venta,boleta,factura'
            ],

            'client_id' => [
                'required',
                'exists:clients,id'
            ],

        ];
    }

    public function messages(): array {
        return [
            'prices.*.required' => 'El precio es obligatorio.',
            'prices.*.numeric' => 'El precio solo debe ser númerico.',
            'prices.*.min' => 'El precio no puede ser negativo.',
            'unit_ids.*.exists' => 'La unidad seleccionada no existe.',
            'voucher.in' => 'El tipo de voucher es nota de venta, boleta o factura.',
            'client_id.exists' => 'El cliente seleccionado no existe',
            'prices.required' => 'Debe agregar al menos un producto al carrito.',
            'unit_ids.required' => 'Debe seleccionar al menos una unidad.',
            'voucher.required' => 'El tipo de comprobante es obligatorio.',
            'client_id.required' => 'Debe seleccionar un cliente.'
        ];
    }

    public function attributes(): array {
        return [
            'prices' => 'precio',
            'client_id' => 'cliente',
            'unit_ids' => 'unidad',
            'voucher' => 'voucher'
        ];
    }
}
