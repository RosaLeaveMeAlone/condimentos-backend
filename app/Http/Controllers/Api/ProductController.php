<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Product\StoreProductRequest;
use App\Http\Requests\Product\UpdateProductRequest;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

/**
 * @group Product management
 *
 * APIs for managing product
 */
class ProductController extends Controller
{
    /**
     * List products
     * 
     * List products with pagination
     */
    public function index(Request $request)
    {
        $rules = [
            'category_id' => 'nullable|string|exists:categories,id',
        ];
        
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json([
                'errors' => $validator->errors(),
            ], 422); 
        }
        
        $page = $request->query('page', 1);
        $perPage = $request->query('per_page', 20);
        $categoryId = $request->query('category_id', null);
        
        $products = Product::filter(
            categoryId: $categoryId,
        )->paginate();

        return response()->json([
            'data' => $products,
        ]);
    }

    /**
     * Create Product
     */
    public function store(StoreProductRequest $request)
    {
        $data = $request->validated();
        $product = Product::create($data);

        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Show Product
     */
    public function show(Product $product)
    {
        $product->load('category');
        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Update Product
     */
    public function update(UpdateProductRequest $request, Product $product)
    {
        $data = $request->validated();
        $product->update($data);

        return response()->json([
            'data' => $product,
        ]);
    }

    /**
     * Delete Product
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'message' => 'Product deleted',
        ]);
    }
}
