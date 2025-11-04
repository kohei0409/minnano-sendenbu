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
        // Use Scout search when keyword is provided
        if ($request->filled('keyword')) {
            $keyword = $request->keyword;

            // Start with Scout search
            $scoutQuery = Store::search($keyword)
                ->where('status', 'active');

            // Get the IDs from Scout search results
            $searchResults = $scoutQuery->get();
            $storeIds = $searchResults->pluck('id')->toArray();

            // If no results from Scout, return empty collection
            if (empty($storeIds)) {
                $query = Store::query()->whereRaw('1 = 0'); // Empty query
            } else {
                // Build query with Scout results
                $query = Store::query()
                    ->whereIn('id', $storeIds)
                    ->where('status', 'active');

                // Apply filters
                if ($request->filled('category_id')) {
                    $query->byCategory($request->category_id);
                }

                if ($request->filled('area_id')) {
                    $query->byArea($request->area_id);
                }

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
                        // Maintain Scout relevance order by preserving the order of IDs
                        $query->orderByRaw('FIELD(id, ' . implode(',', $storeIds) . ')');
                }
            }
        } else {
            // Use traditional query builder when no keyword
            $query = Store::query()->where('status', 'active');

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
