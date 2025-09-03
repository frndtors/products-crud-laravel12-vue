<?php

namespace App\Contracts;

use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Product Repository Interface
 *
 * Defines the contract for product data access operations
 */
interface ProductRepositoryInterface
{
    /**
     * Get paginated products with optional search
     */
    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator;

    /**
     * Find product by ID
     */
    public function findById(int $id): ?Product;

    /**
     * Create a new product
     */
    public function create(ProductDTO $productDTO): Product;

    /**
     * Update an existing product
     */
    public function update(int $id, ProductDTO $productDTO): ?Product;

    /**
     * Delete a product
     */
    public function delete(int $id): bool;

    /**
     * Get all products
     */
    public function getAll(): \Illuminate\Database\Eloquent\Collection;

    /**
     * Search products by name
     */
    public function searchByName(string $name): \Illuminate\Database\Eloquent\Collection;

    /**
     * Get products in stock
     */
    public function getInStock(): \Illuminate\Database\Eloquent\Collection;
}
