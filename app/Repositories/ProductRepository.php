<?php

namespace App\Repositories;

use App\Contracts\ProductRepositoryInterface;
use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private readonly Product $model
    ) {}

    public function getPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%")
                ->orWhere('description', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function create(ProductDTO $productDTO): Product
    {
        return $this->model->create($productDTO->toArray());
    }

    public function update(int $id, ProductDTO $productDTO): ?Product
    {
        $product = $this->findById($id);

        if (!$product) {
            return null;
        }

        $product->update($productDTO->toArray());
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        $product = $this->findById($id);
        return $product ? $product->delete() : false;
    }

    public function getAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function searchByName(string $name): Collection
    {
        return $this->model->where('name', 'like', "%{$name}%")->get();
    }

    public function getLowStockProducts(int $threshold = 5): Collection
    {
        return $this->model->where('stock', '<=', $threshold)
            ->where('stock', '>', 0)
            ->get();
    }

    public function getOutOfStockProducts(): Collection
    {
        return $this->model->where('stock', 0)->get();
    }

    public function getInStock(): Collection
    {
        return $this->model->where('stock', '>', 0)->get();
    }

    public function getCount(): int
    {
        return $this->model->count();
    }

    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }
}
