<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

/**
 * Create Product Request
 *
 * Handles validation for product creation
 */
class CreateProductRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Adjust based on your authorization logic
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'name' => [
                'required',
                'string',
                'max:255',
                'min:2',
                'unique:products,name'
            ],
            'price' => [
                'required',
                'numeric',
                'min:0.01',
                'max:999999.99',
                'decimal:0,2'
            ],
            'stock' => [
                'required',
                'integer',
                'min:0',
                'max:999999'
            ],
            'description' => [
                'nullable',
                'string',
                'max:1000'
            ]
        ];
    }

    /**
     * Get custom error messages for validation rules.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'name.required' => 'The product name is required.',
            'name.min' => 'The product name must be at least 2 characters.',
            'name.unique' => 'A product with this name already exists.',
            'price.required' => 'The product price is required.',
            'price.min' => 'The product price must be greater than 0.',
            'price.decimal' => 'The product price must have at most 2 decimal places.',
            'stock.required' => 'The product stock is required.',
            'stock.min' => 'The product stock cannot be negative.',
            'description.max' => 'The product description cannot exceed 1000 characters.'
        ];
    }

    /**
     * Get custom attribute names for validation errors.
     *
     * @return array<string, string>
     */
    public function attributes(): array
    {
        return [
            'name' => 'product name',
            'price' => 'product price',
            'stock' => 'product stock',
            'description' => 'product description'
        ];
    }
}
