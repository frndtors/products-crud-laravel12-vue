<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

/**
 * Product Service
 *
 * Business logic layer for product operations
 */
class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    /**
     * Get paginated products with search functionality
     */
    public function getPaginatedProducts(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->productRepository->getPaginated($perPage, $search);
    }

    /**
     * Find a product by ID
     */
    public function findProduct(int $id): ?ProductDTO
    {
        $product = $this->productRepository->findById($id);

        return $product ? ProductDTO::fromModel($product) : null;
    }

    /**
     * Create a new product with business validation
     */
    public function createProduct(array $data): ProductDTO
    {
        // Business validations
        $this->validateProductData($data);

        $productDTO = ProductDTO::fromArray($data);
        $product = $this->productRepository->create($productDTO);

        return ProductDTO::fromModel($product);
    }

    /**
     * Update an existing product
     */
    public function updateProduct(int $id, array $data): ?ProductDTO
    {
        // Business validations
        $this->validateProductData($data);

        $productDTO = ProductDTO::fromArray($data);
        $product = $this->productRepository->update($id, $productDTO);

        return $product ? ProductDTO::fromModel($product) : null;
    }

    /**
     * Delete a product
     */
    public function deleteProduct(int $id): bool
    {
        return $this->productRepository->delete($id);
    }

    /**
     * Get all products as DTOs
     */
    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll()
            ->map(fn(Product $product) => ProductDTO::fromModel($product));
    }

    /**
     * Search products by name
     */
    public function searchProducts(string $name): Collection
    {
        return $this->productRepository->searchByName($name)
            ->map(fn(Product $product) => ProductDTO::fromModel($product));
    }

    /**
     * Get products that are in stock
     */
    public function getProductsInStock(): Collection
    {
        return $this->productRepository->getInStock()
            ->map(fn(Product $product) => ProductDTO::fromModel($product));
    }

    /**
     * Business validation for product data
     */
    private function validateProductData(array $data): void
    {
        // Business rule: Price must be positive
        if (isset($data['price']) && $data['price'] <= 0) {
            throw new \InvalidArgumentException('Product price must be greater than 0');
        }

        // Business rule: Stock cannot be negative
        if (isset($data['stock']) && $data['stock'] < 0) {
            throw new \InvalidArgumentException('Product stock cannot be negative');
        }

        // Business rule: Name must be meaningful
        if (isset($data['name']) && strlen(trim($data['name'])) < 2) {
            throw new \InvalidArgumentException('Product name must be at least 2 characters long');
        }
    }
}
