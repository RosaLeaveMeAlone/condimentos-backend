<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
    ];

    // -----------------------------------------------------------------------------------------------------------------
    // @ Relations
    // -----------------------------------------------------------------------------------------------------------------

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
    ) {
        $query = static::query();

        if ($search) {
            // $query->where(
            //     fn ($query) => $query
            //         ->where('products.name', 'ILIKE', "%$search%")
            //         ->orWhere('products.description', 'ILIKE', "%$search%")
            // );
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
