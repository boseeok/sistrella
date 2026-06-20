<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Repositories\ProductRepository;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(private readonly ProductRepository $products)
    {
    }

    public function index(Request $request): JsonResponse
    {
        $filters = $request->only(['search', 'category_id', 'min_price', 'max_price', 'in_stock', 'sort', 'per_page']);

        return response()->json($this->products->filtered($filters));
    }

    public function show(Product $product): JsonResponse
    {
        abort_unless($product->is_active, 404);

        return response()->json(
            $product->load(['images', 'category', 'variants.attributeValues', 'approvedReviews'])
        );
    }

    public function categories(): JsonResponse
    {
        return response()->json(Category::active()->roots()->with('children')->get());
    }
}
