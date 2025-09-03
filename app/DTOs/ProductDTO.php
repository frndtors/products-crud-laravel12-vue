<?php

namespace App\DTOs;

/**
 * Product Data Transfer Object
 *
 * Used for transferring product data between layers
 */
class ProductDTO
{
    public function __construct(
        public readonly ?int $id = null,
        public readonly string $name = '',
        public readonly float $price = 0.0,
        public readonly int $stock = 0,
        public readonly ?string $description = null,
        public readonly ?\Carbon\Carbon $created_at = null,
        public readonly ?\Carbon\Carbon $updated_at = null
    ) {}

    /**
     * Create DTO from array
     */
    public static function fromArray(array $data): self
    {
        return new self(
            id: $data['id'] ?? null,
            name: $data['name'] ?? '',
            price: (float) ($data['price'] ?? 0.0),
            stock: (int) ($data['stock'] ?? 0),
            description: $data['description'] ?? null,
            created_at: isset($data['created_at']) ? \Carbon\Carbon::parse($data['created_at']) : null,
            updated_at: isset($data['updated_at']) ? \Carbon\Carbon::parse($data['updated_at']) : null
        );
    }

    /**
     * Create DTO from model
     */
    public static function fromModel(\App\Models\Product $product): self
    {
        return new self(
            id: $product->id,
            name: $product->name,
            price: $product->price,
            stock: $product->stock,
            description: $product->description,
            created_at: $product->created_at,
            updated_at: $product->updated_at
        );
    }

    /**
     * Convert DTO to array
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'price' => $this->price,
            'stock' => $this->stock,
            'description' => $this->description,
            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    /**
     * Get formatted price for display
     */
    public function getFormattedPrice(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Check if product is in stock
     */
    public function isInStock(): bool
    {
        return $this->stock > 0;
    }
}
