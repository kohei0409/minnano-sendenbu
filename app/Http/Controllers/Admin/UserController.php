<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class UserController extends Controller
{
    /**
     * Display a listing of users with filters.
     */
    public function index(Request $request): View
    {
        $query = User::query()->with('store');

        // Filter by role
        if ($request->has('role') && $request->role !== '') {
            $query->where('role', $request->role);
        }

        // Filter by user level
        if ($request->has('user_level') && $request->user_level !== '') {
            $query->where('user_level', $request->user_level);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Filter by store
        if ($request->has('store_id') && $request->store_id !== '') {
            $query->where('store_id', $request->store_id);
        }

        // Search by name, username, or email
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('username', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->latest()->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): View
    {
        $user->load('store', 'approvedBy', 'permissions');
        return view('admin.users.show', compact('user'));
    }

    /**
     * Update the user's subscription level.
     */
    public function updateLevel(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'user_level' => ['required', 'in:free,premium1,premium2'],
        ]);

        $user->update(['user_level' => $validated['user_level']]);

        return redirect()->back()->with('success', 'ユーザーレベルを更新しました。');
    }

    /**
     * Update the user's status.
     */
    public function updateStatus(Request $request, User $user): RedirectResponse
    {
        $validated = $request->validate([
            'status' => ['required', 'in:pending,active,suspended'],
        ]);

        $oldStatus = $user->status;
        $newStatus = $validated['status'];

        $user->update(['status' => $newStatus]);

        // Log status change
        \Log::info("User status changed", [
            'user_id' => $user->id,
            'admin_id' => $request->user()->id,
            'old_status' => $oldStatus,
            'new_status' => $newStatus,
        ]);

        return redirect()->back()->with('success', 'ユーザーステータスを更新しました。');
    }
}
