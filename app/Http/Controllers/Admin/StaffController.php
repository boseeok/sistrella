<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Role;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class StaffController extends Controller
{
    /**
     * Staff = users holding any non-customer role.
     */
    public function index(): View
    {
        return view('admin.staff.index', [
            'staff' => User::whereHas('roles', fn ($q) => $q->where('name', '!=', 'customer'))
                ->with('roles')->latest()->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.staff.create', [
            'roles' => Role::where('name', '!=', 'customer')->orderBy('display_name')->get(),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'roles'    => ['required', 'array', 'min:1'],
            'roles.*'  => ['string', 'exists:roles,name'],
        ]);

        $user = User::create([
            'name'              => $data['name'],
            'email'             => $data['email'],
            'phone'             => $data['phone'] ?? null,
            'password'          => Hash::make($data['password']),
            'is_active'         => true,
            'email_verified_at' => now(),
        ]);

        $user->syncRoles($data['roles']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member added.');
    }

    public function edit(User $staff): View
    {
        return view('admin.staff.edit', [
            'staff'    => $staff->load('roles'),
            'roles'    => Role::where('name', '!=', 'customer')->orderBy('display_name')->get(),
            'assigned' => $staff->roles->pluck('name')->all(),
        ]);
    }

    public function update(User $staff, Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($staff->id)],
            'phone'    => ['nullable', 'string', 'max:30'],
            'password' => ['nullable', 'confirmed', Password::defaults()],
            'is_active' => ['nullable', 'boolean'],
            'roles'    => ['required', 'array', 'min:1'],
            'roles.*'  => ['string', 'exists:roles,name'],
        ]);

        $staff->update([
            'name'      => $data['name'],
            'email'     => $data['email'],
            'phone'     => $data['phone'] ?? null,
            'is_active' => $request->boolean('is_active'),
        ]);

        if (! empty($data['password'])) {
            $staff->update(['password' => Hash::make($data['password'])]);
        }

        $staff->syncRoles($data['roles']);

        return redirect()->route('admin.staff.index')->with('success', 'Staff member updated.');
    }

    public function destroy(User $staff): RedirectResponse
    {
        if ($staff->id === auth()->id()) {
            return back()->with('error', 'You cannot remove your own account.');
        }

        $staff->delete();

        return back()->with('success', 'Staff member removed.');
    }
}
