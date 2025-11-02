<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CouponController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $store = auth()->user()->store;
        $coupons = $store->coupons()
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('store.coupons.index', compact('coupons'));
    }

    public function create()
    {
        return view('store.coupons.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,amount,free_item',
            'discount_value' => 'required|numeric|min:0',
            'code' => 'nullable|string|max:50|unique:coupons,code',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'conditions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $store = auth()->user()->store;

        // Generate code if not provided
        if (empty($validated['code'])) {
            $validated['code'] = strtoupper(Str::random(8));
        }

        $validated['is_active'] = $request->has('is_active');

        $store->coupons()->create($validated);

        return redirect()
            ->route('store.coupons.index')
            ->with('success', 'クーポンを作成しました');
    }

    public function edit(Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        return view('store.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $this->authorize('update', $coupon);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'discount_type' => 'required|in:percentage,amount,free_item',
            'discount_value' => 'required|numeric|min:0',
            'usage_limit' => 'nullable|integer|min:1',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'conditions' => 'nullable|string',
            'is_active' => 'boolean',
        ]);

        $validated['is_active'] = $request->has('is_active');

        $coupon->update($validated);

        return redirect()
            ->route('store.coupons.index')
            ->with('success', 'クーポンを更新しました');
    }

    public function destroy(Coupon $coupon)
    {
        $this->authorize('delete', $coupon);

        $coupon->delete();

        return redirect()
            ->route('store.coupons.index')
            ->with('success', 'クーポンを削除しました');
    }
}
