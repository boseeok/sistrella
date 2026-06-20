<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class InventoryController extends Controller
{
    public function index(Request $request): View
    {
        $query = Product::with('category')->where('track_inventory', true);

        if ($request->get('filter') === 'low') {
            $query->lowStock();
        } elseif ($request->get('filter') === 'out') {
            $query->where('stock', '<=', 0);
        }

        if ($search = $request->get('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")->orWhere('sku', 'like', "%{$search}%"));
        }

        return view('admin.inventory.index', [
            'products' => $query->orderBy('stock')->paginate(25)->withQueryString(),
            'filters'  => $request->only(['filter', 'search']),
        ]);
    }

    public function update(Product $product, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'stock'               => ['required', 'integer', 'min:0'],
            'low_stock_threshold' => ['required', 'integer', 'min:0'],
        ]);

        $product->update($data);

        return back()->with('success', "Stock for {$product->name} updated.");
    }
}
