<?php

namespace App\Repositories;

use App\DTOs\ProductDTO;
use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository
{
    public function __construct(
        private readonly Product $model
    ) {}

    public function findAll(): Collection
    {
        return $this->model->latest()->get();
    }

    public function findPaginated(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = $this->model->query();

        if ($search) {
            $query->where('name', 'like', "%{$search}%");
        }

        return $query->latest()->paginate($perPage);
    }

    public function findById(int $id): ?Product
    {
        return $this->model->find($id);
    }

    public function create(ProductDTO $dto): Product
    {
        return $this->model->create($dto->toArray());
    }

    public function update(Product $product, ProductDTO $dto): bool
    {
        return $product->update($dto->toArray());
    }

    public function delete(Product $product): bool
    {
        return $product->delete();
    }

    public function exists(int $id): bool
    {
        return $this->model->where('id', $id)->exists();
    }

    public function getCount(): int
    {
        return $this->model->count();
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
}
