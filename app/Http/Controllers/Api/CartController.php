<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Cart\AddProductToCartRequest;
use App\Http\Requests\Cart\GetCartRequest;
use App\Models\Cart;
use App\Http\Requests\Cart\StoreCartRequest;
use App\Http\Requests\Cart\UpdateCartRequest;
use App\Http\Services\CartService;
use App\Models\CartProduct;
use App\Models\Product;

//TODO: Recordar calcular el monto en base a precios de oferta tambien
class CartController extends Controller
{

    public function __construct(
        protected CartService $cartService,
    )
    {}

    public function addProduct(AddProductToCartRequest $request)
    {
        $product = Product::findOrFail($request->product_id);

        if($request->is_unit && !$product->unit_availability) return response()->json(['message' => 'The product is not available by unit'],400);
        if(!$request->is_unit && !$product->weight_availability) return response()->json(['message' => 'The product is not available by weight'],400);

        $cartProduct = CartProduct::firstWhere([
            'cart_id' => $request->cart->id,
            'product_id' => $request->product_id,
        ]);

        if($request->quantity == 0) {
            $cartProduct->delete();
            return response()->json([
                'message' => 'Product removed.',
            ]);
        }

        if ($cartProduct) {
            $cartProduct->update([
                'quantity' => $request->quantity,
                'is_unit' => $request->is_unit,
            ]);
        } else {
            CartProduct::create([
                'cart_id' => $request->cart->id,
                'product_id' => $request->product_id,
                'quantity' => $request->quantity,
                'is_unit' => $request->is_unit,
            ]);
        }

        return response()->json([
            'message' => 'Product added.',
        ]);
    }

    public function closeCart(Cart $cart)
    {
        $cart->update([
            'is_closed' => true, // this is usefull?
        ]);

        return response()->json([
            'message' => 'Cart closed.'
        ]);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store()
    {
        $token = $this->cartService->generateToken();

        $cart = Cart::create([
            'token' => $token,
        ]);

        return response()->json([
            'data' => [
                'token' => $token,
                'products_quantity' => 0,
                'total' => '0.00',
                'products' => [],
            ],
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show(Cart $cart)
    {
        $products = $cart->products()->get();
        $total = 0;

        if ($products->isNotEmpty()) {
            $total = $this->cartService->getTotal($products);
        }

        $count = $products->count();

        $total = $this->cartService->getTotal($products);

        $formattedProducts = $products->map(function ($product) {
            // Calcula el total para este producto (asume que ya tienes una función para esto)
            $total = $this->cartService->getProductTotal($product,1);
            
            return [
                'id' => $product->id,
                'name' => $product->name,
                'quantity' => $product->pivot->quantity,
                'total' => $total,
            ];
        });
        return response()->json([
            'data' => [
                'token' => $cart->token,
                'products_quantity' => $count,
                'total' => $total,
                'products' => $formattedProducts,
            ],
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCartRequest $request, Cart $cart)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cart $cart)
    {
        //
    }
}
