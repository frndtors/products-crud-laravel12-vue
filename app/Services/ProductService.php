<?php

namespace App\Services;

use App\DTOs\ProductDTO;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class ProductService
{
    public function __construct(
        private readonly ProductRepository $repository
    ) {}

    public function getAllProducts(): Collection
    {
        return $this->repository->findAll();
    }

    public function getProductsPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        return $this->repository->findPaginated($perPage, $search);
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
        $product = $this->getProductById($id);

        $this->repository->update($product, $dto);

        return $product->fresh();
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->getProductById($id);

        return $this->repository->delete($product);
    }

    public function getProductStats(): array
    {
        return [
            'total_products' => $this->repository->getCount(),
            'low_stock_products' => $this->repository->getLowStockProducts(),
            'out_of_stock_products' => $this->repository->getOutOfStockProducts(),
        ];
    }

    public function productExists(int $id): bool
    {
        return $this->repository->exists($id);
    }
}
