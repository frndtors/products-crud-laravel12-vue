<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'min:2', 'max:255'],
            'price' => ['required', 'numeric', 'min:0.01', 'max:999999.99'],
            'stock' => ['required', 'integer', 'min:0', 'max:999999'],
            'description' => ['nullable', 'string', 'max:1000'],
        ];
    }

    /**
     * Get custom messages for validator errors.
     */
    public function messages(): array
    {
        return [
            'name.required' => 'El nombre del producto es obligatorio.',
            'name.min' => 'El nombre del producto debe tener al menos 2 caracteres.',
            'name.max' => 'El nombre del producto no puede exceder los 255 caracteres.',
            'price.required' => 'El precio del producto es obligatorio.',
            'price.numeric' => 'El precio debe ser un número válido.',
            'price.min' => 'El precio debe ser al menos $0.01.',
            'price.max' => 'El precio no puede exceder los $999,999.99.',
            'stock.required' => 'La cantidad en inventario es obligatoria.',
            'stock.integer' => 'La cantidad en inventario debe ser un número entero.',
            'stock.min' => 'La cantidad en inventario no puede ser negativa.',
            'stock.max' => 'La cantidad en inventario no puede exceder las 999,999 unidades.',
            'description.max' => 'La descripción no puede exceder los 1000 caracteres.',
        ];
    }
}
