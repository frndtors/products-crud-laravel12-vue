<?php

namespace App\DTOs;

use App\Http\Requests\Products\UpdateProductRequest;

class ProductUpdateDTO
{
    public function __construct(
        public readonly int $id,
        public readonly string $name,
        public readonly float $price,
        public readonly int $stock,
        public readonly ?string $description = null
    ) {}

    public static function fromRequest(UpdateProductRequest $request, int $id): self
    {
        return new self(
            id: $id,
            name: $request->validated('name'),
            price: (float) $request->validated('price'),
            stock: (int) $request->validated('stock'),
            description: $request->validated('description')
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
        ];
    }
}
