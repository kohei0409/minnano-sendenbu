<?php

namespace App\Http\Controllers;

use App\Mail\ReviewPosted;
use App\Models\Review;
use App\Models\Store;
use App\Models\ReviewImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;

class ReviewController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:customer');
    }

    public function create(Store $store)
    {
        return view('public.reviews.create', compact('store'));
    }

    public function store(Request $request, Store $store)
    {
        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visit_date' => 'nullable|date',
            'images.*' => 'nullable|image|max:5120',
        ]);

        $review = $store->reviews()->create([
            'customer_id' => auth('customer')->id(),
            'rating' => $validated['rating'],
            'title' => $validated['title'],
            'content' => $validated['content'],
            'visit_date' => $validated['visit_date'] ?? null,
            'status' => 'pending',
        ]);

        // Handle image uploads
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $path = $image->store('review-images', 'public');

                ReviewImage::create([
                    'review_id' => $review->id,
                    'image_path' => $path,
                ]);
            }
        }

        // Send notification email to store
        if ($store->email) {
            Mail::to($store->email)->send(new ReviewPosted($review));
        }

        return redirect()
            ->route('stores.show', $store)
            ->with('success', 'レビューを投稿しました。承認後に公開されます。');
    }

    public function edit(Review $review)
    {
        $this->authorize('update', $review);

        return view('public.reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        $this->authorize('update', $review);

        $validated = $request->validate([
            'rating' => 'required|numeric|min:1|max:5',
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'visit_date' => 'nullable|date',
        ]);

        $review->update($validated);

        return redirect()
            ->route('stores.show', $review->store)
            ->with('success', 'レビューを更新しました。');
    }

    public function destroy(Review $review)
    {
        $this->authorize('delete', $review);

        $review->delete();

        return redirect()
            ->route('customer.reviews')
            ->with('success', 'レビューを削除しました。');
    }
}
