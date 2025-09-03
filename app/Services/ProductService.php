<?php

namespace App\Services;

use App\Contracts\ProductRepositoryInterface;
use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->repository->getAll();
    }

    public function getProductsPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        // Validate pagination parameters
        $perPage = $this->validatePerPage($perPage);

        return $this->repository->getPaginated($perPage, $search);
    }

    public function getProductById(int $id): Product
    {
        $product = $this->repository->findById($id);

        if (!$product) {
            throw new ModelNotFoundException("Product with ID {$id} not found.");
        }

        return $product;
    }

    public function createProduct(ProductDTO $dto): Product
    {
        return $this->repository->create($dto);
    }

    public function updateProduct(int $id, ProductDTO $dto): Product
    {
        $product = $this->repository->update($id, $dto);

        if (!$product) {
            throw new ModelNotFoundException("Product with ID {$id} not found.");
        }

        return $product;
    }

    public function deleteProduct(int $id): bool
    {
        if (!$this->repository->exists($id)) {
            throw new ModelNotFoundException("Product with ID {$id} not found.");
        }

        return $this->repository->delete($id);
    }

    public function getProductStats(): array
    {
        return [
            'total_products' => $this->repository->getCount(),
            'low_stock_products' => $this->repository->getLowStockProducts(),
            'out_of_stock_products' => $this->repository->getOutOfStockProducts(),
            'in_stock_products' => $this->repository->getInStock(),
        ];
    }

    public function productExists(int $id): bool
    {
        return $this->repository->exists($id);
    }

    public function searchProducts(string $query): Collection
    {
        return $this->repository->searchByName($query);
    }

    private function validatePerPage(int $perPage): int
    {
        return max(1, min($perPage, 100)); // Between 1 and 100
    }
}
