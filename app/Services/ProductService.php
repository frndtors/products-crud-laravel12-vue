<?php

namespace App\Services;

use App\DTOs\ProductCreateDTO;
use App\DTOs\ProductUpdateDTO;
use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductService
{
    public function __construct(
        private readonly ProductRepositoryInterface $productRepository
    ) {}

    public function getProductsPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        // Validar y limpiar parámetros
        $perPage = max(5, min(100, $perPage));
        $search = !empty($search) ? trim($search) : null;

        return $this->productRepository->getPaginatedWithSearch($perPage, $search);
    }

    public function getProductById(int $id): Product
    {
        $product = $this->productRepository->findById($id);

        if (!$product) {
            throw new \Exception("Producto con ID {$id} no encontrado.");
        }

        return $product;
    }

    public function createProduct(ProductCreateDTO $dto): Product
    {
        return $this->productRepository->create([
            'name' => $dto->name,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'description' => $dto->description,
        ]);
    }

    public function updateProduct(ProductUpdateDTO $dto): Product
    {
        $product = $this->getProductById($dto->id);

        return $this->productRepository->update($product->id, [
            'name' => $dto->name,
            'price' => $dto->price,
            'stock' => $dto->stock,
            'description' => $dto->description,
        ]);
    }

    public function deleteProduct(int $id): bool
    {
        $product = $this->getProductById($id);
        return $this->productRepository->delete($product->id);
    }

    public function getAllProducts(): Collection
    {
        return $this->productRepository->getAll();
    }

    public function searchProducts(string $query): Collection
    {
        if (empty(trim($query))) {
            return collect();
        }

        return $this->productRepository->search(trim($query));
    }

    public function getProductsByStock(int $maxStock = 5): Collection
    {
        return $this->productRepository->findByStockLessThanOrEqual($maxStock);
    }

    public function getOutOfStockProducts(): Collection
    {
        return $this->productRepository->findByStock(0);
    }
}
