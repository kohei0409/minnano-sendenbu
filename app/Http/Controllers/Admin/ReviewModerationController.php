<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Mail\ReviewApproved;
use App\Mail\ReviewRejected;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ReviewModerationController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('role:admin');
    }

    public function index(Request $request)
    {
        $query = Review::with(['store', 'customer']);

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        } else {
            // Default to pending reviews
            $query->pending();
        }

        $reviews = $query->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('admin.reviews.index', compact('reviews'));
    }

    public function show(Review $review)
    {
        $review->load(['store', 'customer', 'images', 'reply']);

        return view('admin.reviews.show', compact('review'));
    }

    public function approve(Review $review)
    {
        $review->update([
            'status' => 'published',
            'published_at' => now(),
        ]);

        // Update store rating
        $review->store->updateRating();

        // Send approval email to customer
        if ($review->customer && $review->customer->email) {
            Mail::to($review->customer->email)->send(new ReviewApproved($review));
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'レビューを承認しました');
    }

    public function reject(Request $request, Review $review)
    {
        $validated = $request->validate([
            'reason' => 'required|string|max:1000',
        ]);

        $review->update([
            'status' => 'rejected',
        ]);

        // Send rejection email to customer
        if ($review->customer && $review->customer->email) {
            Mail::to($review->customer->email)->send(new ReviewRejected($review, $validated['reason']));
        }

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'レビューを却下しました');
    }

    public function destroy(Review $review)
    {
        $store = $review->store;
        $review->delete();

        // Update store rating after deletion
        $store->updateRating();

        return redirect()
            ->route('admin.reviews.index')
            ->with('success', 'レビューを削除しました');
    }
}
