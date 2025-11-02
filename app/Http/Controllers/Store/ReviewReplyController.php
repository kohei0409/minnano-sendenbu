<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\Review;
use App\Models\ReviewReply;
use Illuminate\Http\Request;

class ReviewReplyController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function store(Request $request, Review $review)
    {
        // Check if store owns this review
        if ($review->store_id !== auth()->user()->store_id) {
            abort(403);
        }

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        ReviewReply::create([
            'review_id' => $review->id,
            'user_id' => auth()->id(),
            'content' => $validated['content'],
        ]);

        return redirect()
            ->back()
            ->with('success', 'レビューに返信しました');
    }

    public function update(Request $request, ReviewReply $reviewReply)
    {
        $this->authorize('update', $reviewReply);

        $validated = $request->validate([
            'content' => 'required|string',
        ]);

        $reviewReply->update($validated);

        return redirect()
            ->back()
            ->with('success', '返信を更新しました');
    }

    public function destroy(ReviewReply $reviewReply)
    {
        $this->authorize('delete', $reviewReply);

        $reviewReply->delete();

        return redirect()
            ->back()
            ->with('success', '返信を削除しました');
    }
}
