<?php

namespace App\DTOs;

use App\Http\Requests\Products\CreateProductRequest;
use App\Http\Requests\Products\UpdateProductRequest;

class ProductDTO
{
    public function __construct(
        public readonly string $name,
        public readonly float $price,
        public readonly int $stock,
        public readonly ?string $description = null
    ) {}

    public static function fromCreateRequest(CreateProductRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            price: (float) $request->validated('price'),
            stock: (int) $request->validated('stock'),
            description: $request->validated('description')
        );
    }

    public static function fromUpdateRequest(UpdateProductRequest $request): self
    {
        return new self(
            name: $request->validated('name'),
            price: (float) $request->validated('price'),
            stock: (int) $request->validated('stock'),
            description: $request->validated('description')
        );
    }

    public function toArray(): array
    {
        return [
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
        ];
    }
}
