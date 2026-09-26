<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductCollection;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Admin CRUD for occasions (Birthday, Valentine's…) and curated collections.
 */
class CollectionController extends Controller
{
    public function index(Request $request): View
    {
        $type = array_key_exists($request->query('type'), ProductCollection::TYPES) ? $request->query('type') : null;

        return view('admin.collections.index', [
            'collections' => ProductCollection::withCount('products')
                ->when($type, fn ($q) => $q->where('type', $type))
                ->orderBy('type')->ordered()->paginate(30)->withQueryString(),
            'type' => $type,
        ]);
    }

    public function create(Request $request): View
    {
        return view('admin.collections.create', [
            'products'      => $this->productOptions(),
            'selected'      => [],
            'defaultType'   => $request->query('type') === 'curated' ? 'curated' : 'occasion',
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $collection = ProductCollection::create($this->validated($request));
        $collection->products()->sync($request->input('product_ids', []));

        return redirect()->route('admin.collections.index')->with('success', "“{$collection->name}” created.");
    }

    public function show(ProductCollection $collection): RedirectResponse
    {
        return redirect()->route('admin.collections.edit', $collection);
    }

    public function edit(ProductCollection $collection): View
    {
        return view('admin.collections.edit', [
            'collection' => $collection,
            'products'   => $this->productOptions(),
            'selected'   => $collection->products()->pluck('products.id')->all(),
        ]);
    }

    public function update(ProductCollection $collection, Request $request): RedirectResponse
    {
        $collection->update($this->validated($request, $collection));
        $collection->products()->sync($request->input('product_ids', []));

        return redirect()->route('admin.collections.index')->with('success', "“{$collection->name}” updated.");
    }

    public function destroy(ProductCollection $collection): RedirectResponse
    {
        if ($collection->image && ! str_contains($collection->image, '/stock/')) {
            Storage::disk('public')->delete($collection->image);
        }
        $collection->delete(); // pivot rows cascade; products are untouched

        return back()->with('success', 'Collection deleted.');
    }

    private function validated(Request $request, ?ProductCollection $collection = null): array
    {
        $data = $request->validate([
            'type'             => ['required', Rule::in(array_keys(ProductCollection::TYPES))],
            'name'             => ['required', 'string', 'max:120'],
            'slug'             => ['nullable', 'alpha_dash', 'max:120', Rule::unique('collections', 'slug')->ignore($collection)],
            'tagline'          => ['nullable', 'string', 'max:160'],
            'description'      => ['nullable', 'string', 'max:1000'],
            'icon'             => ['nullable', 'regex:/^[a-z0-9-]{1,40}$/'],
            'sort_order'       => ['nullable', 'integer', 'min:0'],
            'image'            => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'meta_title'       => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:255'],
            'product_ids'      => ['nullable', 'array'],
            'product_ids.*'    => ['integer', 'exists:products,id'],
        ], ['icon.regex' => 'Use a Bootstrap Icons name such as "gift" or "balloon-heart".']);

        unset($data['product_ids']);
        $data['icon']        = isset($data['icon']) ? preg_replace('/^bi-/', '', $data['icon']) : null;
        $data['is_active']   = $request->boolean('is_active');
        $data['is_featured'] = $request->boolean('is_featured');
        $data['sort_order']  = $data['sort_order'] ?? 0;
        $data['slug']        = $data['slug'] ?? ($collection?->slug);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('collections', 'public');
        } else {
            unset($data['image']);
        }

        return $data;
    }

    private function productOptions()
    {
        return Product::with('category:id,name')->orderBy('name')->get(['id', 'name', 'category_id', 'is_active']);
    }
}
