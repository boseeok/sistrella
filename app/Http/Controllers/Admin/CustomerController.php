<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CustomerController extends Controller
{
    public function index(Request $request): View
    {
        $query = User::whereHas('roles', fn ($q) => $q->where('name', 'customer'))
            ->withCount('orders')
            ->withSum('orders', 'grand_total')
            ->latest();

        if ($search = $request->get('search')) {
            $query->where(fn ($q) => $q->where('name', 'like', "%{$search}%")
                ->orWhere('email', 'like', "%{$search}%")
                ->orWhere('phone', 'like', "%{$search}%"));
        }

        return view('admin.customers.index', [
            'customers' => $query->paginate(20)->withQueryString(),
            'filters'   => $request->only('search'),
        ]);
    }

    public function show(User $user): View
    {
        return view('admin.customers.show', [
            'customer' => $user->load(['addresses', 'customRequests']),
            'orders'   => $user->orders()->withCount('items')->latest()->paginate(10),
        ]);
    }

    public function toggle(User $user): RedirectResponse
    {
        $user->update(['is_active' => ! $user->is_active]);

        return back()->with('success', $user->is_active ? 'Customer activated.' : 'Customer deactivated.');
    }
}
