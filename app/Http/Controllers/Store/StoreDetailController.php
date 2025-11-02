<?php

namespace App\Http\Controllers\Store;

use App\Http\Controllers\Controller;
use App\Models\StoreImage;
use App\Models\BusinessHour;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class StoreDetailController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function edit()
    {
        $store = auth()->user()->store->load(['storeDetail', 'images', 'businessHours', 'tags']);

        return view('store.details.edit', compact('store'));
    }

    public function update(Request $request)
    {
        $store = auth()->user()->store;

        $validated = $request->validate([
            'category_id' => 'nullable|exists:categories,id',
            'area_id' => 'nullable|exists:areas,id',
            'postal_code' => 'nullable|string|max:10',
            'prefecture' => 'nullable|string|max:100',
            'city' => 'nullable|string|max:100',
            'street_address' => 'nullable|string|max:255',
            'building' => 'nullable|string|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            // Store detail fields
            'description' => 'nullable|string',
            'access_info' => 'nullable|string',
            'parking_info' => 'nullable|string',
            'payment_methods' => 'nullable|string',
            'website_url' => 'nullable|url',
            'facebook_url' => 'nullable|url',
            'instagram_url' => 'nullable|url',
            'twitter_url' => 'nullable|url',
            'seats' => 'nullable|integer|min:0',
            'private_rooms' => 'boolean',
            'smoking' => 'nullable|in:allowed,separated,prohibited',
            'wifi' => 'boolean',
            'power_outlet' => 'boolean',
            'credit_card' => 'boolean',
        ]);

        // Update store
        $store->update([
            'category_id' => $validated['category_id'] ?? null,
            'area_id' => $validated['area_id'] ?? null,
            'postal_code' => $validated['postal_code'] ?? null,
            'prefecture' => $validated['prefecture'] ?? null,
            'city' => $validated['city'] ?? null,
            'street_address' => $validated['street_address'] ?? null,
            'building' => $validated['building'] ?? null,
            'latitude' => $validated['latitude'] ?? null,
            'longitude' => $validated['longitude'] ?? null,
        ]);

        // Update or create store detail
        $store->storeDetail()->updateOrCreate(
            ['store_id' => $store->id],
            [
                'description' => $validated['description'] ?? null,
                'access_info' => $validated['access_info'] ?? null,
                'parking_info' => $validated['parking_info'] ?? null,
                'payment_methods' => $validated['payment_methods'] ?? null,
                'website_url' => $validated['website_url'] ?? null,
                'facebook_url' => $validated['facebook_url'] ?? null,
                'instagram_url' => $validated['instagram_url'] ?? null,
                'twitter_url' => $validated['twitter_url'] ?? null,
                'seats' => $validated['seats'] ?? null,
                'private_rooms' => $request->has('private_rooms'),
                'smoking' => $validated['smoking'] ?? 'prohibited',
                'wifi' => $request->has('wifi'),
                'power_outlet' => $request->has('power_outlet'),
                'credit_card' => $request->has('credit_card'),
            ]
        );

        return redirect()
            ->route('store.details.edit')
            ->with('success', '店舗情報を更新しました');
    }

    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|max:5120',
            'image_type' => 'required|in:main,exterior,interior,menu,other',
            'caption' => 'nullable|string|max:255',
        ]);

        $store = auth()->user()->store;

        $path = $request->file('image')->store('store-images', 'public');

        $maxOrder = $store->images()->max('display_order') ?? 0;

        StoreImage::create([
            'store_id' => $store->id,
            'image_path' => $path,
            'image_type' => $request->image_type,
            'caption' => $request->caption,
            'display_order' => $maxOrder + 1,
        ]);

        return redirect()
            ->route('store.details.edit')
            ->with('success', '画像をアップロードしました');
    }

    public function deleteImage(StoreImage $image)
    {
        $this->authorize('delete', $image);

        Storage::disk('public')->delete($image->image_path);
        $image->delete();

        return redirect()
            ->route('store.details.edit')
            ->with('success', '画像を削除しました');
    }

    public function updateBusinessHours(Request $request)
    {
        $validated = $request->validate([
            'hours' => 'required|array',
            'hours.*.day_of_week' => 'required|integer|between:0,6',
            'hours.*.is_closed' => 'boolean',
            'hours.*.open_time' => 'nullable|date_format:H:i',
            'hours.*.close_time' => 'nullable|date_format:H:i',
            'hours.*.break_start' => 'nullable|date_format:H:i',
            'hours.*.break_end' => 'nullable|date_format:H:i',
        ]);

        $store = auth()->user()->store;

        // Delete existing business hours
        $store->businessHours()->delete();

        // Create new business hours
        foreach ($validated['hours'] as $hour) {
            $store->businessHours()->create([
                'day_of_week' => $hour['day_of_week'],
                'is_closed' => $hour['is_closed'] ?? false,
                'open_time' => $hour['open_time'] ?? null,
                'close_time' => $hour['close_time'] ?? null,
                'break_start' => $hour['break_start'] ?? null,
                'break_end' => $hour['break_end'] ?? null,
            ]);
        }

        return redirect()
            ->route('store.details.edit')
            ->with('success', '営業時間を更新しました');
    }
}
