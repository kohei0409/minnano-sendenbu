<?php

namespace App\Http\Controllers;

use App\Models\Store;
use App\Models\Category;
use App\Models\Area;
use Illuminate\Http\Request;

class PublicStoreController extends Controller
{
    public function home()
    {
        // Featured stores
        $featuredStores = Store::where('status', 'active')
            ->featured()
            ->with(['category', 'area', 'images'])
            ->limit(6)
            ->get();

        // New stores
        $newStores = Store::where('status', 'active')
            ->with(['category', 'area', 'images'])
            ->orderBy('created_at', 'desc')
            ->limit(6)
            ->get();

        // Popular stores (by rating)
        $popularStores = Store::where('status', 'active')
            ->withReviews()
            ->with(['category', 'area', 'images'])
            ->orderBy('average_rating', 'desc')
            ->orderBy('review_count', 'desc')
            ->limit(6)
            ->get();

        // Categories
        $categories = Category::rootCategories()->get();

        // Areas (prefectures)
        $areas = Area::prefectures()->limit(12)->get();

        return view('public.home', compact('featuredStores', 'newStores', 'popularStores', 'categories', 'areas'));
    }

    public function index(Request $request)
    {
        $query = Store::query()->where('status', 'active');

        // Keyword search using LIKE (will use Scout when installed - see SEARCH_SETUP.md)
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;
            $query->where(function($q) use ($keyword) {
                $q->where('store_name', 'like', '%' . $keyword . '%')
                  ->orWhere('industry', 'like', '%' . $keyword . '%')
                  ->orWhere('street_address', 'like', '%' . $keyword . '%')
                  ->orWhere('city', 'like', '%' . $keyword . '%')
                  ->orWhere('prefecture', 'like', '%' . $keyword . '%')
                  ->orWhereHas('storeDetail', function($q2) use ($keyword) {
                      $q2->where('description', 'like', '%' . $keyword . '%');
                  });
            });
        }

        // Filter by category
        if ($request->filled('category_id')) {
            $query->byCategory($request->category_id);
        }

        // Filter by area
        if ($request->filled('area_id')) {
            $query->byArea($request->area_id);
        }

        // Filter by rating
        if ($request->filled('min_rating')) {
            $query->highRated($request->min_rating);
        }

        // Sort
        $sortBy = $request->get('sort', 'created_at');
        $sortOrder = $request->get('order', 'desc');

        switch ($sortBy) {
            case 'rating':
                $query->orderBy('average_rating', $sortOrder);
                break;
            case 'reviews':
                $query->orderBy('review_count', $sortOrder);
                break;
            case 'views':
                $query->orderBy('view_count', $sortOrder);
                break;
            default:
                $query->orderBy('created_at', $sortOrder);
        }

        $stores = $query->with(['category', 'area'])->paginate(20);
        $categories = Category::rootCategories()->get();
        $areas = Area::prefectures()->get();

        return view('public.stores.index', compact('stores', 'categories', 'areas'));
    }

    public function show($id)
    {
        $store = Store::with([
            'category',
            'area',
            'storeDetail',
            'images',
            'businessHours',
            'menus',
            'publishedReviews.customer',
            'activeCoupons',
            'tags'
        ])->findOrFail($id);

        // Increment view count
        $store->incrementViewCount();

        return view('public.stores.show', compact('store'));
    }
}
