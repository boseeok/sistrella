<?php

namespace App\Http\Controllers\Shop;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class AccountController extends Controller
{
    public function dashboard(): View
    {
        $user = auth()->user();

        return view('shop.account.dashboard', [
            'ordersCount'   => $user->orders()->count(),
            'pendingCount'  => $user->orders()->whereIn('status', ['pending_payment', 'payment_submitted'])->count(),
            'wishlistCount' => $user->wishlist()->count(),
            'recentOrders'  => $user->orders()->latest()->limit(5)->get(),
        ]);
    }

    public function profile(): View
    {
        return view('shop.account.profile', ['user' => auth()->user()]);
    }

    public function updateProfile(Request $request): RedirectResponse
    {
        $user = auth()->user();
        $data = $request->validate([
            'name'  => ['required', 'string', 'max:255'],
            'phone' => ['nullable', 'string', 'max:30'],
            'email' => ['required', 'email', 'max:255', 'unique:users,email,'.$user->id],
        ]);

        $user->update($data);

        return back()->with('success', 'Profile updated.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'password'         => ['required', 'confirmed', Password::defaults()],
        ]);

        auth()->user()->update(['password' => $request->password]);

        return back()->with('success', 'Password changed.');
    }

    public function orders(): View
    {
        return view('shop.account.orders', [
            'orders' => auth()->user()->orders()->withCount('items')->latest()->paginate(10),
        ]);
    }

    public function showOrder(Order $order): View
    {
        abort_unless($order->user_id === auth()->id(), 403);

        return view('shop.account.order-show', [
            'order' => $order->load('items', 'payments', 'statusHistory.changedBy'),
        ]);
    }

    public function addresses(): View
    {
        return view('shop.account.addresses', [
            'addresses' => auth()->user()->addresses()->latest()->get(),
        ]);
    }

    public function storeAddress(Request $request): RedirectResponse
    {
        $data = $this->validateAddress($request);
        $this->normalizeDefault($data);

        auth()->user()->addresses()->create($data);

        return back()->with('success', 'Address added.');
    }

    public function updateAddress(Address $address, Request $request): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);

        $data = $this->validateAddress($request);
        $this->normalizeDefault($data);

        $address->update($data);

        return back()->with('success', 'Address updated.');
    }

    public function destroyAddress(Address $address): RedirectResponse
    {
        abort_unless($address->user_id === auth()->id(), 403);
        $address->delete();

        return back()->with('success', 'Address removed.');
    }

    private function validateAddress(Request $request): array
    {
        return $request->validate([
            'label'       => ['nullable', 'string', 'max:60'],
            'full_name'   => ['required', 'string', 'max:255'],
            'phone'       => ['required', 'string', 'max:30'],
            'line1'       => ['required', 'string', 'max:255'],
            'line2'       => ['nullable', 'string', 'max:255'],
            'city'        => ['required', 'string', 'max:120'],
            'district'    => ['nullable', 'string', 'max:120'],
            'province'    => ['nullable', 'string', 'max:120'],
            'postal_code' => ['nullable', 'string', 'max:20'],
            'is_default'  => ['nullable', 'boolean'],
        ]);
    }

    /**
     * Ensure only one default address per user.
     */
    private function normalizeDefault(array &$data): void
    {
        $data['is_default'] = (bool) ($data['is_default'] ?? false);
        if ($data['is_default']) {
            auth()->user()->addresses()->update(['is_default' => false]);
        }
    }
}
