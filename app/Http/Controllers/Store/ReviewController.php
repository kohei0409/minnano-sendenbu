<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * レビュー一覧
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store) {
            abort(403, '店舗が登録されていません。');
        }

        $query = $store->reviews()->with(['customer', 'reviewReply']);

        // フィルター: 返信状態
        if ($request->filled('reply_status')) {
            if ($request->reply_status === 'replied') {
                $query->has('reviewReply');
            } elseif ($request->reply_status === 'not_replied') {
                $query->doesntHave('reviewReply');
            }
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        // 統計情報
        $stats = [
            'total' => $store->reviews()->count(),
            'not_replied' => $store->reviews()->doesntHave('reviewReply')->count(),
            'average_rating' => $store->reviews()->where('status', 'published')->avg('rating') ?? 0,
        ];

        return view('store.reviews.index', compact('reviews', 'stats'));
    }

    /**
     * レビュー詳細
     */
    public function show(Review $review)
    {
        $user = Auth::user();
        $store = $user->store;

        if (!$store || $review->store_id !== $store->id) {
            abort(403, 'このレビューにアクセスする権限がありません。');
        }

        $review->load(['customer', 'reviewImages', 'reviewReply']);

        return view('store.reviews.show', compact('review'));
    }
}
