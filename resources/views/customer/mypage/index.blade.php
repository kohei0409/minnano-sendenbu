<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            マイページ
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ウェルカムメッセージ -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-md p-6 mb-6">
                <h3 class="text-2xl font-bold text-white mb-2">
                    ようこそ、{{ Auth::user()->name }}さん
                </h3>
                <p class="text-orange-100">
                    あなたの予約やレビューを管理できます
                </p>
            </div>

            <!-- 統計カード -->
            @php
                $reservationsCount = Auth::user()->reservations()->count();
                $reviewsCount = Auth::user()->reviews()->count();
                $favoritesCount = Auth::user()->favorites()->count();
            @endphp

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <!-- 予約数 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                                <svg class="h-8 w-8 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">予約数</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $reservationsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- レビュー数 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">レビュー数</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $reviewsCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- お気に入り数 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-red-100 rounded-md p-3">
                                <svg class="h-8 w-8 text-red-600" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">お気に入り</p>
                                <p class="text-3xl font-bold text-gray-900">{{ $favoritesCount }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- クイックリンク -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">クイックリンク</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                        <a href="{{ route('customer.mypage.reservations') }}" class="block p-4 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">予約履歴</h4>
                                    <p class="text-sm text-gray-600">{{ $reservationsCount }}件</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('customer.mypage.reviews') }}" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">レビュー履歴</h4>
                                    <p class="text-sm text-gray-600">{{ $reviewsCount }}件</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('customer.favorites.index') }}" class="block p-4 bg-red-50 hover:bg-red-100 rounded-lg border border-red-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-red-600 mr-3" fill="currentColor" viewBox="0 0 24 24">
                                    <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">お気に入り</h4>
                                    <p class="text-sm text-gray-600">{{ $favoritesCount }}件</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('customer.mypage.edit-profile') }}" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">プロフィール編集</h4>
                                    <p class="text-sm text-gray-600">情報を更新</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 最新の予約 -->
                @php
                    $recentReservations = Auth::user()->reservations()
                        ->with('store')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">最新の予約</h3>
                            <a href="{{ route('customer.mypage.reservations') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                                すべて見る
                            </a>
                        </div>

                        @if($recentReservations->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentReservations as $reservation)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-orange-300 transition">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <h4 class="font-bold text-gray-900 mb-1">
                                                    {{ $reservation->store->store_name }}
                                                </h4>
                                                <p class="text-sm text-gray-600 mb-2">
                                                    <span class="font-medium">{{ $reservation->reservation_date->format('Y年m月d日') }}</span>
                                                    <span class="ml-2">{{ substr($reservation->reservation_time, 0, 5) }}</span>
                                                    <span class="ml-2">{{ $reservation->number_of_people }}名</span>
                                                </p>
                                            </div>
                                            <div>
                                                @if($reservation->status === 'pending')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        承認待ち
                                                    </span>
                                                @elseif($reservation->status === 'confirmed')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                        予約確定
                                                    </span>
                                                @elseif($reservation->status === 'cancelled')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                        キャンセル
                                                    </span>
                                                @elseif($reservation->status === 'completed')
                                                    <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        完了
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <p class="text-gray-500">予約履歴はありません</p>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- 最新のレビュー -->
                @php
                    $recentReviews = Auth::user()->reviews()
                        ->with('store')
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center justify-between mb-4">
                            <h3 class="text-lg font-bold text-gray-900">最新のレビュー</h3>
                            <a href="{{ route('customer.mypage.reviews') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                すべて見る
                            </a>
                        </div>

                        @if($recentReviews->count() > 0)
                            <div class="space-y-4">
                                @foreach($recentReviews as $review)
                                    <div class="border border-gray-200 rounded-lg p-4 hover:border-blue-300 transition">
                                        <div class="flex items-start justify-between mb-2">
                                            <h4 class="font-bold text-gray-900">
                                                {{ $review->store->store_name }}
                                            </h4>
                                            @if($review->status === 'pending')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    承認待ち
                                                </span>
                                            @elseif($review->status === 'published')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                    公開中
                                                </span>
                                            @elseif($review->status === 'rejected')
                                                <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                    却下
                                                </span>
                                            @endif
                                        </div>
                                        <div class="flex items-center mb-2">
                                            <div class="flex text-yellow-400">
                                                @for($i = 1; $i <= 5; $i++)
                                                    @if($i <= $review->rating)
                                                        <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                        </svg>
                                                    @else
                                                        <svg class="w-4 h-4 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                        </svg>
                                                    @endif
                                                @endfor
                                            </div>
                                            <span class="text-sm text-gray-500 ml-2">
                                                {{ $review->created_at->format('Y/m/d') }}
                                            </span>
                                        </div>
                                        @if($review->response_comment)
                                            <p class="text-xs text-blue-600 font-medium">店舗から返信あり</p>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center py-8">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                                <p class="text-gray-500">レビュー履歴はありません</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
