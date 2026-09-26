<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductCollection;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Services\ActivityLogger;
use App\Services\ProductVariantService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class ProductController extends Controller
{
    public function __construct(
        private readonly ActivityLogger $logger,
        private readonly ProductVariantService $variants,
    ) {
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
            'categories' => Category::with('parent')->orderBy('name')->get(),
            'filters'    => $request->only(['search', 'category_id', 'status']),
        ]);
    }

    public function create(): View
    {
        return view('admin.products.create', [
            'categories'  => Category::with('parent.parent')->orderBy('name')->get(),
            'collections' => ProductCollection::ordered()->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request);

        $product = Product::create($data);
        $this->syncImages($product, $request);
        $this->syncExtras($product, $request);
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
            'product'     => $product->load(['images', 'collections', 'variants.attributeValues.attribute']),
            'categories'  => Category::with('parent.parent')->orderBy('name')->get(),
            'collections' => ProductCollection::ordered()->get(),
        ]);
    }

    public function update(Product $product, Request $request): RedirectResponse
    {
        $data = $this->validateProduct($request, $product);

        $product->update($data);
        $this->syncImages($product, $request);
        $this->syncExtras($product, $request);
        $this->logger->log('product.updated', "Updated product {$product->name}", $product);

        return back()->with('success', 'Product updated.');
    }

    public function destroy(Product $product): RedirectResponse
    {
        $product->delete();
        $this->logger->log('product.deleted', "Deleted product {$product->name}", $product);

        return redirect()->route('admin.products.index')->with('success', 'Product deleted.');
    }

    /**
     * Occasion tags and colour/size variants (validated in validateProduct).
     */
    private function syncExtras(Product $product, Request $request): void
    {
        if ($request->has('collections_present')) {
            $product->collections()->sync($request->input('collection_ids', []));
        }

        if ($request->has('variants_present')) {
            $this->variants->sync($product, $request->input('variants', []));
        }
    }

    public function makePrimaryImage(Product $product, ProductImage $image): RedirectResponse
    {
        $product->images()->whereKeyNot($image->getKey())->update(['is_primary' => false]);
        $image->forceFill(['is_primary' => true])->save();

        return back()->with('success', 'Primary image updated.');
    }

    public function destroyImage(Product $product, ProductImage $image): RedirectResponse
    {
        $image->delete();

        // Remove the uploaded file unless another record still uses it
        // (seeded stock photos are shared between products/categories).
        if (! Str::startsWith($image->path, ['http://', 'https://'])
            && ! ProductImage::where('path', $image->path)->exists()) {
            Storage::disk('public')->delete($image->path);
        }

        if ($image->is_primary) {
            $product->images()->orderBy('sort_order')->first()?->update(['is_primary' => true]);
        }

        return back()->with('success', 'Image removed.');
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
            // Occasions / collections
            'collection_ids'       => ['nullable', 'array'],
            'collection_ids.*'     => ['integer', 'exists:collections,id'],
            // Colour / size variants
            'variants'               => ['nullable', 'array', 'max:60'],
            'variants.*.id'          => ['nullable', 'integer'],
            'variants.*.color'       => ['nullable', 'string', 'max:40'],
            'variants.*.color_code'  => ['nullable', 'regex:/^#[0-9a-fA-F]{6}$/'],
            'variants.*.size'        => ['nullable', 'string', 'max:40'],
            'variants.*.price'       => ['nullable', 'numeric', 'min:0'],
            'variants.*.stock'       => ['nullable', 'integer', 'min:0'],
            'variants.*.sku'         => ['nullable', 'string', 'max:60', 'distinct'],
        ]);

        // Variant SKUs must be unique across the catalogue.
        foreach ($request->input('variants', []) as $i => $row) {
            if (filled($row['sku'] ?? null) && empty($row['_delete'])
                && ProductVariant::where('sku', $row['sku'])->whereKeyNot($row['id'] ?? 0)->exists()) {
                throw ValidationException::withMessages(["variants.{$i}.sku" => "SKU {$row['sku']} is already used by another variant."]);
            }
        }
        unset($data['collection_ids'], $data['variants']);

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

        $request->validate(['images.*' => ['image', 'mimes:jpg,jpeg,png,webp,gif', 'max:4096']]);

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
