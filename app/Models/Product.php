<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * Product Entity Model
 *
 * @property int $id
 * @property string $name
 * @property float $price
 * @property int $stock
 * @property string|null $description
 * @property \Illuminate\Support\Carbon $created_at
 * @property \Illuminate\Support\Carbon $updated_at
 */
class Product extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     */
    protected $table = 'products';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'price',
        'stock',
        'description',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the attributes that should be visible in arrays.
     *
     * @var array<int, string>
     */
    protected $visible = [
        'id',
        'name',
        'price',
        'stock',
        'description',
        'created_at',
        'updated_at',
    ];

    /**
     * Scope to search products by name
     */
    public function scopeSearchByName($query, string $name)
    {
        return $query->where('name', 'LIKE', '%' . $name . '%');
    }

    /**
     * Scope to filter products with stock available
     */
    public function scopeInStock($query)
    {
        return $query->where('stock', '>', 0);
    }
}
