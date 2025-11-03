<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            店舗ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- 店舗情報サマリー -->
            @if(Auth::user()->store)
                @php
                    $store = Auth::user()->store;
                    $pendingReservations = $store->reservations()->where('status', 'pending')->count();
                    $todayReservations = $store->reservations()
                        ->where('reservation_date', today())
                        ->whereIn('status', ['confirmed', 'pending'])
                        ->count();
                    $totalReviews = $store->reviews()->where('status', 'published')->count();
                    $avgRating = $store->reviews()->where('status', 'published')->avg('rating') ?? 0;
                @endphp

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-orange-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">本日の予約</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $todayReservations }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">承認待ち予約</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $pendingReservations }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">平均評価</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ number_format($avgRating, 1) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                    <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">レビュー数</p>
                                    <p class="text-2xl font-bold text-gray-900">{{ $totalReviews }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- 管理メニュー -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">管理メニュー</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            <a href="{{ route('store.reservations.index') }}" class="block p-4 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">予約管理</h4>
                                        <p class="text-sm text-gray-600">予約の確認・承認</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('store.menus.index') }}" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">メニュー管理</h4>
                                        <p class="text-sm text-gray-600">メニューの登録・編集</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('store.coupons.index') }}" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">クーポン管理</h4>
                                        <p class="text-sm text-gray-600">クーポンの作成・管理</p>
                                    </div>
                                </div>
                            </a>

                            <a href="{{ route('store.details.edit') }}" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">店舗情報編集</h4>
                                        <p class="text-sm text-gray-600">基本情報・営業時間</p>
                                    </div>
                                </div>
                            </a>

                            @if(Auth::user()->isStoreOwner())
                                <a href="{{ route('store.staff.index') }}" class="block p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg border border-yellow-200 transition">
                                    <div class="flex items-center">
                                        <svg class="h-8 w-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                        </svg>
                                        <div>
                                            <h4 class="font-bold text-gray-900">スタッフ管理</h4>
                                            <p class="text-sm text-gray-600">スタッフの追加・編集</p>
                                        </div>
                                    </div>
                                </a>
                            @endif

                            <a href="{{ route('stores.show', $store) }}" target="_blank" class="block p-4 bg-gray-50 hover:bg-gray-100 rounded-lg border border-gray-200 transition">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-gray-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">店舗ページを見る</h4>
                                        <p class="text-sm text-gray-600">公開ページを確認</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- 最近の予約 -->
                @php
                    $recentReservations = $store->reservations()
                        ->orderBy('created_at', 'desc')
                        ->limit(5)
                        ->get();
                @endphp
                @if($recentReservations->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">最近の予約</h3>
                                <a href="{{ route('store.reservations.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                                    すべて見る →
                                </a>
                            </div>
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">予約日時</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">お客様名</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">人数</th>
                                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($recentReservations as $reservation)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $reservation->reservation_date->format('Y/m/d') }} {{ substr($reservation->reservation_time, 0, 5) }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $reservation->customer_name }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    {{ $reservation->number_of_people }}名
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @if($reservation->status === 'pending')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            承認待ち
                                                        </span>
                                                    @elseif($reservation->status === 'confirmed')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                            確定
                                                        </span>
                                                    @elseif($reservation->status === 'cancelled')
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                            キャンセル
                                                        </span>
                                                    @else
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                            {{ $reservation->status }}
                                                        </span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @endif
            @else
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900">
                        <p>店舗情報が登録されていません</p>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
