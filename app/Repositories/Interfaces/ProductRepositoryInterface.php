<?php

namespace App\Repositories\Interfaces;

use App\Models\Product;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface ProductRepositoryInterface
{
    public function getAll(): Collection;

    public function findById(int $id): ?Product;

    public function create(array $data): Product;

    public function update(int $id, array $data): Product;

    public function delete(int $id): bool;

    public function getPaginatedWithSearch(int $perPage = 10, ?string $search = null): LengthAwarePaginator;

    public function search(string $query): Collection;

    public function findByStock(int $stock): Collection;

    public function findByStockLessThanOrEqual(int $maxStock): Collection;
}
