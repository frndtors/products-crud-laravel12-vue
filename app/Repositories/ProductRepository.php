<?php

namespace App\Repositories;

use App\Models\Product;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class ProductRepository implements ProductRepositoryInterface
{
    public function getAll(): Collection
    {
        return Product::orderBy('created_at', 'desc')->get();
    }

    public function findById(int $id): ?Product
    {
        return Product::find($id);
    }

    public function create(array $data): Product
    {
        return Product::create($data);
    }

    public function update(int $id, array $data): Product
    {
        $product = Product::findOrFail($id);
        $product->update($data);
        return $product->fresh();
    }

    public function delete(int $id): bool
    {
        return Product::destroy($id) > 0;
    }

    public function getPaginatedWithSearch(int $perPage = 10, ?string $search = null): LengthAwarePaginator
    {
        $query = Product::query()->orderBy('created_at', 'desc');

        if (!empty($search)) {
            $searchTerm = '%' . $search . '%';
            $query->where(function ($q) use ($searchTerm) {
                $q->where('name', 'like', $searchTerm)
                    ->orWhere('description', 'like', $searchTerm);
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }

    public function search(string $query): Collection
    {
        $searchTerm = '%' . $query . '%';

        return Product::where('name', 'like', $searchTerm)
            ->orWhere('description', 'like', $searchTerm)
            ->orderBy('name')
            ->get();
    }

    public function findByStock(int $stock): Collection
    {
        return Product::where('stock', $stock)
            ->orderBy('name')
            ->get();
    }

    public function findByStockLessThanOrEqual(int $maxStock): Collection
    {
        return Product::where('stock', '<=', $maxStock)
            ->where('stock', '>', 0)
            ->orderBy('stock')
            ->get();
    }
}
