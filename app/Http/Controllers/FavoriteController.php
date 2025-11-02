<?php

namespace App\Http\Controllers;

use App\Models\Store;
use Illuminate\Http\Request;

class FavoriteController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function index()
    {
        $customer = auth('customer')->user();
        $favorites = $customer->favorites()
            ->with(['category', 'area'])
            ->paginate(20);

        return view('public.favorites.index', compact('favorites'));
    }

    public function toggle(Store $store)
    {
        $customer = auth('customer')->user();

        if ($customer->favorites()->where('store_id', $store->id)->exists()) {
            $customer->favorites()->detach($store->id);
            $store->updateFavoriteCount();

            return response()->json([
                'status' => 'removed',
                'message' => 'お気に入りから削除しました',
            ]);
        } else {
            $customer->favorites()->attach($store->id);
            $store->updateFavoriteCount();

            return response()->json([
                'status' => 'added',
                'message' => 'お気に入りに追加しました',
            ]);
        }
    }
}
