<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Services\ActivityLogger;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(private readonly ActivityLogger $logger)
    {
    }

    public function index(Request $request): View
    {
        $query = Product::with(['category', 'primaryImage'])->latest();

        if ($search = $request->get('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"));
        }
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }
        if ($request->get('status') === 'active') {
            $query->where('is_active', true);
        } elseif ($request->get('status') === 'inactive') {
            $query->where('is_active', false);
        }

        return view('admin.products.index', [
            'products'   => $query->paginate(20)->withQueryString(),
            'categories' => Category::orderBy('name')->get(),
            'filters'    => $request->only(['search', 'category_id', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        $product = Product::create($data);
        $this->syncImages($product, $request);
        $this->logger->log('product.created', "Created product {$product->name}", $product);

        return redirect()->route('admin.products.edit', $product)->with('success', 'Product created.');
    }

    public function show(Product $product): RedirectResponse
    {
        return redirect()->route('admin.products.edit', $product);
    }

    public function edit(Product $product): View
    {
        return view('admin.products.edit', [
            'product'    => $product->load('images'),
            'categories' => Category::orderBy('name')->get(),
        ]);
    }

    public function update(Product $product, Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);

        $product->update($data);
        $this->syncImages($product, $request);
        $this->logger->log('product.updated', "Updated product {$product->name}", $product);

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        $this->logger->log('product.deleted', "Deleted product {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    private function validateProduct(Request $request, ?Product $product = null): array
    {
        $data = $request->validate([
            'name'                 => ['required', 'string', 'max:255'],
            'category_id'          => ['nullable', 'exists:categories,id'],
            'sku'                  => ['nullable', 'string', 'max:60', 'unique:products,sku'.($product ? ','.$product->id : '')],
            'short_description'    => ['nullable', 'string', 'max:500'],
            'description'          => ['nullable', 'string'],
            'price'                => ['required', 'numeric', 'min:0'],
            'compare_at_price'     => ['nullable', 'numeric', 'min:0'],
            'cost_price'           => ['nullable', 'numeric', 'min:0'],
            'stock'                => ['required', 'integer', 'min:0'],
            'low_stock_threshold'  => ['required', 'integer', 'min:0'],
            'track_inventory'      => ['nullable', 'boolean'],
            'type'                 => ['required', 'in:simple,variable,bundle,custom'],
            'flash_sale_price'     => ['nullable', 'numeric', 'min:0'],
            'flash_sale_starts_at' => ['nullable', 'date'],
            'flash_sale_ends_at'   => ['nullable', 'date', 'after_or_equal:flash_sale_starts_at'],
            'is_active'            => ['nullable', 'boolean'],
            'is_featured'          => ['nullable', 'boolean'],
            'is_trending'          => ['nullable', 'boolean'],
            'is_best_seller'       => ['nullable', 'boolean'],
            'is_new_arrival'       => ['nullable', 'boolean'],
            'is_customizable'      => ['nullable', 'boolean'],
            'weight'               => ['nullable', 'numeric', 'min:0'],
            'meta_title'           => ['nullable', 'string', 'max:255'],
            'meta_description'     => ['nullable', 'string', 'max:255'],
        ]);

        foreach (['track_inventory', 'is_active', 'is_featured', 'is_trending', 'is_best_seller', 'is_new_arrival', 'is_customizable'] as $flag) {
            $data[$flag] = $request->boolean($flag);
        }

        if (empty($data['sku'])) {
            unset($data['sku']); // let the model auto-generate
        }

        return $data;
    }

    /**
     * Persist any uploaded gallery images; first uploaded becomes primary
     * when the product has none yet.
     */
    private function syncImages(Product $product, Request $request): void
    {
        if (! $request->hasFile('images')) {
            return;
        }

        $request->validate(['images.*' => ['image', 'max:4096']]);

        $hasPrimary = $product->images()->where('is_primary', true)->exists();

        foreach ($request->file('images') as $file) {
            $path = $file->store('products', 'public');
            $product->images()->create([
                'path'       => $path,
                'is_primary' => ! $hasPrimary,
                'sort_order' => $product->images()->count(),
            ]);
            $hasPrimary = true;
        }
    }
}
