<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'description',
        'image',
        'unit_availability',
        'unit_price',
        'unit_discount_quantity',
        'unit_discount_price',
        'weight_availability',
        'weight_price',
        'weight_discount_quantity',
        'weight_discount_price',
        'unit_name',
        'is_available',
        'category_id',
    ];

    // -----------------------------------------------------------------------------------------------------------------
    // @ Relations
    // -----------------------------------------------------------------------------------------------------------------

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // -----------------------------------------------------------------------------------------------------------------
    // @ Accessors & Mutators
    // -----------------------------------------------------------------------------------------------------------------

    // -----------------------------------------------------------------------------------------------------------------
    // @ Public Functions
    // -----------------------------------------------------------------------------------------------------------------

    // -----------------------------------------------------------------------------------------------------------------
    // @ Private Functions
    // -----------------------------------------------------------------------------------------------------------------

    // -----------------------------------------------------------------------------------------------------------------
    // @ Static Functions
    // -----------------------------------------------------------------------------------------------------------------

    public static function filter(
        $search = null,
        $orderByAttribute = 'id',
        $orderByDirection = 'ASC',
        $categoryId = null,
    ) {
        $query = static::query();

        $query->with(['category']);

        if ($search) {
            $query->where(
                fn ($query) => $query
                    ->where('products.name', 'ILIKE', "%$search%")
                    ->orWhere('products.description', 'ILIKE', "%$search%")
            );
        }

        if ($categoryId) {
            $query->whereHas(
                'category',
                fn ($query) => $query->where('categories.id', $categoryId)
            );
        }

        // sorting
        $handleNulls = strtoupper($orderByDirection) == 'DESC' ? 'NULLS LAST' : 'NULLS FIRST';
        switch ($orderByAttribute) {
            default:
                $query->orderByRaw("$orderByAttribute $orderByDirection $handleNulls");
                break;
        }

        return $query;
    }
}
