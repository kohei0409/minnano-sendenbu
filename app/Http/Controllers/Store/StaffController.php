<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Spatie\Permission\Models\Permission;

class StaffController extends Controller
{
    /**
     * Display a listing of staff members.
     */
    public function index(Request $request): View
    {
        $user = $request->user();

        // Store owners can only see their own staff
        $staff = User::where('store_id', $user->store_id)
            ->where('role', 'store_staff')
            ->with('permissions')
            ->latest()
            ->paginate(20);

        return view('store.staff.index', compact('staff'));
    }

    /**
     * Show the form for creating a new staff member.
     */
    public function create(): View
    {
        $permissions = Permission::all();
        return view('store.staff.create', compact('permissions'));
    }

    /**
     * Store a newly created staff member.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'username' => ['required', 'string', 'max:255', 'unique:'.User::class],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:'.User::class],
            'password' => ['required', 'confirmed', Rules\Password::defaults()],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $staff = User::create([
            'username' => $validated['username'],
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'store_staff',
            'user_level' => 'free',
            'status' => 'active',
            'store_id' => $request->user()->store_id,
            'approved_at' => now(),
            'approved_by' => $request->user()->id,
        ]);

        // Assign permissions
        if (!empty($validated['permissions'])) {
            $staff->givePermissionTo($validated['permissions']);
        }

        return redirect()->route('store.staff.index')
            ->with('success', 'スタッフを作成しました。');
    }

    /**
     * Display the specified staff member.
     */
    public function show(Request $request, User $staff): View
    {
        // Ensure staff belongs to the same store
        if ($staff->store_id !== $request->user()->store_id) {
            abort(403, '権限がありません。');
        }

        $staff->load('permissions');
        return view('store.staff.show', compact('staff'));
    }

    /**
     * Show the form for editing the specified staff member.
     */
    public function edit(Request $request, User $staff): View
    {
        // Ensure staff belongs to the same store
        if ($staff->store_id !== $request->user()->store_id) {
            abort(403, '権限がありません。');
        }

        $permissions = Permission::all();
        $staff->load('permissions');

        return view('store.staff.edit', compact('staff', 'permissions'));
    }

    /**
     * Update the specified staff member.
     */
    public function update(Request $request, User $staff): RedirectResponse
    {
        // Ensure staff belongs to the same store
        if ($staff->store_id !== $request->user()->store_id) {
            abort(403, '権限がありません。');
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:users,email,'.$staff->id],
            'password' => ['nullable', 'confirmed', Rules\Password::defaults()],
            'permissions' => ['nullable', 'array'],
            'permissions.*' => ['exists:permissions,name'],
        ]);

        $updateData = [
            'name' => $validated['name'],
            'email' => $validated['email'],
        ];

        // Only update password if provided
        if (!empty($validated['password'])) {
            $updateData['password'] = Hash::make($validated['password']);
        }

        $staff->update($updateData);

        // Sync permissions
        if (isset($validated['permissions'])) {
            $staff->syncPermissions($validated['permissions']);
        } else {
            $staff->syncPermissions([]);
        }

        return redirect()->route('store.staff.index')
            ->with('success', 'スタッフ情報を更新しました。');
    }

    /**
     * Remove the specified staff member.
     */
    public function destroy(Request $request, User $staff): RedirectResponse
    {
        // Ensure staff belongs to the same store
        if ($staff->store_id !== $request->user()->store_id) {
            abort(403, '権限がありません。');
        }

        // Prevent deleting store owners
        if ($staff->role === 'store_owner') {
            return redirect()->back()->with('error', '店舗オーナーは削除できません。');
        }

        $staff->delete();

        return redirect()->route('store.staff.index')
            ->with('success', 'スタッフを削除しました。');
    }
}
