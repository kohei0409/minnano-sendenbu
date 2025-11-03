<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class MyPageController extends Controller
{
    /**
     * マイページトップ
     */
    public function index()
    {
        $customer = Auth::guard('customer')->user();

        // 最新の予約を取得
        $recentReservations = $customer->reservations()
            ->with('store')
            ->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->limit(5)
            ->get();

        // 最新のレビューを取得
        $recentReviews = $customer->reviews()
            ->with('store')
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        // お気に入り店舗数
        $favoritesCount = $customer->favorites()->count();

        // 統計情報
        $stats = [
            'reservations_count' => $customer->reservations()->count(),
            'reviews_count' => $customer->reviews()->count(),
            'favorites_count' => $favoritesCount,
        ];

        return view('customer.mypage.index', compact('customer', 'recentReservations', 'recentReviews', 'stats'));
    }

    /**
     * 予約履歴一覧
     */
    public function reservations(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $query = $customer->reservations()->with('store');

        // ステータスフィルター
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reservations = $query->orderBy('reservation_date', 'desc')
            ->orderBy('reservation_time', 'desc')
            ->paginate(10);

        return view('customer.mypage.reservations', compact('reservations'));
    }

    /**
     * レビュー履歴一覧
     */
    public function reviews(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $query = $customer->reviews()->with(['store', 'reviewReply']);

        // ステータスフィルター
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(10);

        return view('customer.mypage.reviews', compact('reviews'));
    }

    /**
     * プロフィール編集フォーム表示
     */
    public function editProfile()
    {
        $customer = Auth::guard('customer')->user();

        return view('customer.mypage.edit-profile', compact('customer'));
    }

    /**
     * プロフィール更新
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', 'unique:customers,email,' . $customer->id],
            'phone' => ['nullable', 'string', 'max:20'],
            'birth_date' => ['nullable', 'date'],
            'gender' => ['nullable', 'in:male,female,other'],
            'postal_code' => ['nullable', 'string', 'max:10'],
            'address' => ['nullable', 'string', 'max:500'],
        ]);

        $customer->update($validated);

        return redirect()->route('customer.mypage.edit-profile')
            ->with('success', 'プロフィールを更新しました。');
    }

    /**
     * パスワード変更フォーム表示
     */
    public function editPassword()
    {
        return view('customer.mypage.edit-password');
    }

    /**
     * パスワード更新
     */
    public function updatePassword(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validated = $request->validate([
            'current_password' => ['required', 'current_password:customer'],
            'password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $customer->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('customer.mypage.edit-password')
            ->with('success', 'パスワードを変更しました。');
    }
}
