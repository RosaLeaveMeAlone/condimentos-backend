<?php

namespace App\Http\Services;

use App\Models\Cart;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\Uuid;

class CartService
{
    public static function generateToken()
    {
        do {
            $token = Uuid::uuid4();
        } while (Cart::firstWhere('token', $token));

        return $token;
    }

    public static function getTotal($products)
    {
        $total = 0;
        foreach ($products as $product) {
            $total += self::getProductTotal($product);
        }

        return round($total, 2);
    }

    public static function getProductTotal($product, $quantity = null)
    {
        if (! $quantity) {
            $quantity = $product->pivot->quantity;
        }

        $amount = 0;

        if ($product->pivot->is_unit) {
            if (!$product->unit_availability) {
                Log::error('Product unit is not available but is_unit is set (true).', [
                    'product_id' => $product->id,
                    'unit_availability' => $product->unit_availability,
                    'pivot_data' => $product->pivot
                ]);
                
                throw new \Exception('Unit availability is required for unit products.');
            }
    
            $amount = ($quantity >= $product->unit_discount_quantity) 
            ? $product->unit_discount_price 
            : $product->unit_price;
        }

        if (!$product->pivot->is_unit) {
            if (!$product->weight_availability) {
                Log::error('Product weight is not available but is_unit is set (false).', [
                    'product_id' => $product->id,
                    'weight_availability' => $product->weight_availability,
                    'pivot_data' => $product->pivot
                ]);
                
                throw new \Exception('Weight availability is required for weight products.');
            }
    
            $amount = ($quantity >= $product->weight_discount_quantity) 
            ? $product->weight_discount_price 
            : $product->weight_price;
        }
        

        $total = $amount * $quantity;

        return round($total, 2);
    }

    public static function getProductPrice($product)
    {
        return self::getProductTotal($product, 1);
    }
}
