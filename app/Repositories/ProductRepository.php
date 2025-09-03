<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Product Repository Implementation
 *
 * Handles all database operations for products
 */
class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $model
    ) {}

    /**
     * Get paginated products with optional search
     */
    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($search) {
            $query->searchByName($search);
        }

        return $query->orderBy('created_at', 'desc')
            ->paginate($perPage)
            ->withQueryString();
    }

    /**
     * Find product by ID
     */
    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    /**
     * Create a new product
     */
    public function create(ProductDTO $productDTO): Product
    {
        return $this->model->create([
            'name' => $productDTO->name,
            'price' => $productDTO->price,
            'stock' => $productDTO->stock,
            'description' => $productDTO->description,
        ]);
    }

    /**
     * Update an existing product
     */
    public function update(int $id, ProductDTO $productDTO): ?Product
    {
        $product = $this->findById($id);

        if (!$product) {
            return null;
        }

        $product->update([
            'name' => $productDTO->name,
            'price' => $productDTO->price,
            'stock' => $productDTO->stock,
            'description' => $productDTO->description,
        ]);

        return $product->fresh();
    }

    /**
     * Delete a product
     */
    public function delete(int $id): bool
    {
        $product = $this->findById($id);

        if (!$product) {
            return false;
        }

        return $product->delete();
    }

    /**
     * Get all products
     */
    public function getAll(): Collection
    {
        return $this->model->orderBy('name')->get();
    }

    /**
     * Search products by name
     */
    public function searchByName(string $name): Collection
    {
        return $this->model->searchByName($name)->get();
    }

    /**
     * Get products in stock
     */
    public function getInStock(): Collection
    {
        return $this->model->inStock()->get();
    }
}
