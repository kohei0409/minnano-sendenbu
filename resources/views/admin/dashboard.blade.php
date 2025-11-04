<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            管理者ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                $totalStores = \App\Models\Store::count();
                $activeStores = \App\Models\Store::where('status', 'active')->count();
                $totalCustomers = \App\Models\Customer::where('status', 'active')->count();
                $pendingApplications = \App\Models\StoreApplication::where('status', 'pending')->count();
                $pendingReviews = \App\Models\Review::where('status', 'pending')->count();
                $todayReservations = \App\Models\Reservation::whereDate('reservation_date', today())->count();
                $totalReviews = \App\Models\Review::where('status', 'published')->count();
                $avgRating = \App\Models\Review::where('status', 'published')->avg('rating') ?? 0;

                // 今日のアクティビティ
                $todayReservationsCount = \App\Models\Reservation::whereDate('created_at', today())->count();
                $todayReviewsCount = \App\Models\Review::whereDate('created_at', today())->count();
                $todayUsersCount = \App\Models\Customer::whereDate('created_at', today())->count();

                // 過去6ヶ月の統計データ
                $monthlyStores = [];
                $monthlyReviews = [];
                $monthLabels = [];
                for ($i = 5; $i >= 0; $i--) {
                    $date = now()->subMonths($i);
                    $monthLabels[] = $date->format('Y年n月');
                    $monthlyStores[] = \App\Models\Store::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count();
                    $monthlyReviews[] = \App\Models\Review::whereYear('created_at', $date->year)
                        ->whereMonth('created_at', $date->month)->count();
                }

                // 人気の店舗TOP5
                $topStores = \App\Models\Store::withCount('reviews')
                    ->with('reviews')
                    ->where('status', 'active')
                    ->having('reviews_count', '>', 0)
                    ->orderBy('reviews_count', 'desc')
                    ->take(5)
                    ->get()
                    ->map(function($store) {
                        $store->avg_rating = $store->reviews()->where('status', 'published')->avg('rating') ?? 0;
                        return $store;
                    });

                // システムステータス
                try {
                    \DB::connection()->getPdo();
                    $dbStatus = 'online';
                } catch (\Exception $e) {
                    $dbStatus = 'offline';
                }

                $storageUsed = disk_free_space('/') !== false ?
                    round((disk_total_space('/') - disk_free_space('/')) / disk_total_space('/') * 100, 1) : 0;
            @endphp

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-blue-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">掲載店舗数</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $activeStores }}</p>
                                <p class="text-xs text-gray-500">総数: {{ $totalStores }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-green-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">登録ユーザー</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $totalCustomers }}</p>
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
                                <p class="text-sm font-medium text-gray-600">承認待ち</p>
                                <p class="text-2xl font-bold text-gray-900">{{ $pendingApplications + $pendingReviews }}</p>
                                <p class="text-xs text-gray-500">申請: {{ $pendingApplications }} / レビュー: {{ $pendingReviews }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <div class="flex items-center">
                            <div class="flex-shrink-0 bg-purple-100 rounded-md p-3">
                                <svg class="h-6 w-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                </svg>
                            </div>
                            <div class="ml-4">
                                <p class="text-sm font-medium text-gray-600">平均評価</p>
                                <p class="text-2xl font-bold text-gray-900">{{ number_format($avgRating, 1) }}</p>
                                <p class="text-xs text-gray-500">レビュー: {{ $totalReviews }}件</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 今日のアクティビティサマリー -->
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 overflow-hidden shadow-lg sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-white mb-4">今日のアクティビティ</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white text-opacity-80">新規予約</p>
                                    <p class="text-3xl font-bold text-white">{{ $todayReservationsCount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white text-opacity-80">新規レビュー</p>
                                    <p class="text-3xl font-bold text-white">{{ $todayReviewsCount }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="bg-white bg-opacity-20 rounded-lg p-4 backdrop-blur-sm">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-10 w-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-white text-opacity-80">新規ユーザー</p>
                                    <p class="text-3xl font-bold text-white">{{ $todayUsersCount }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- システムステータス -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">システムステータス</h3>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 7v10c0 2.21 3.582 4 8 4s8-1.79 8-4V7M4 7c0 2.21 3.582 4 8 4s8-1.79 8-4M4 7c0-2.21 3.582-4 8-4s8 1.79 8 4m0 5c0 2.21-3.582 4-8 4s-8-1.79-8-4"/>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600">データベース接続</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $dbStatus === 'online' ? '正常' : 'エラー' }}</p>
                                </div>
                            </div>
                            @if($dbStatus === 'online')
                                <span class="inline-flex items-center justify-center w-3 h-3 rounded-full bg-green-500 animate-pulse"></span>
                            @else
                                <span class="inline-flex items-center justify-center w-3 h-3 rounded-full bg-red-500 animate-pulse"></span>
                            @endif
                        </div>
                        <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                            <div class="flex items-center flex-1">
                                <div class="flex-shrink-0">
                                    <svg class="h-8 w-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/>
                                    </svg>
                                </div>
                                <div class="ml-4 flex-1">
                                    <p class="text-sm font-medium text-gray-600">ストレージ使用状況</p>
                                    <p class="text-lg font-bold text-gray-900">{{ $storageUsed }}%</p>
                                    <div class="w-full bg-gray-200 rounded-full h-2 mt-2">
                                        <div class="bg-blue-600 h-2 rounded-full" style="width: {{ min($storageUsed, 100) }}%"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- 月別統計グラフエリア -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
                <!-- 新規店舗登録数の推移 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">過去6ヶ月の新規店舗登録数</h3>
                        <canvas id="storesChart" height="200"></canvas>
                    </div>
                </div>

                <!-- レビュー投稿数の推移 -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-4">過去6ヶ月のレビュー投稿数</h3>
                        <canvas id="reviewsChart" height="200"></canvas>
                    </div>
                </div>
            </div>

            <!-- 人気の店舗TOP5 -->
            @if($topStores->count() > 0)
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">人気の店舗TOP5</h3>
                    <div class="space-y-4">
                        @foreach($topStores as $index => $store)
                            <div class="flex items-center p-4 bg-gradient-to-r from-gray-50 to-white rounded-lg border border-gray-200 hover:shadow-md transition">
                                <div class="flex-shrink-0 w-12 h-12 flex items-center justify-center">
                                    @if($index === 0)
                                        <span class="text-3xl font-bold text-yellow-500">1</span>
                                    @elseif($index === 1)
                                        <span class="text-3xl font-bold text-gray-400">2</span>
                                    @elseif($index === 2)
                                        <span class="text-3xl font-bold text-orange-600">3</span>
                                    @else
                                        <span class="text-2xl font-bold text-gray-300">{{ $index + 1 }}</span>
                                    @endif
                                </div>
                                <div class="ml-4 flex-1">
                                    <h4 class="font-bold text-gray-900 text-lg">{{ $store->store_name }}</h4>
                                    <p class="text-sm text-gray-600">{{ $store->category->name ?? 'カテゴリなし' }}</p>
                                </div>
                                <div class="text-right">
                                    <div class="flex items-center justify-end mb-1">
                                        <svg class="w-5 h-5 text-yellow-400 fill-current mr-1" viewBox="0 0 20 20">
                                            <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                        </svg>
                                        <span class="text-lg font-bold text-gray-900">{{ number_format($store->avg_rating, 1) }}</span>
                                    </div>
                                    <p class="text-sm text-gray-600">{{ $store->reviews_count }}件のレビュー</p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
            @endif

            <!-- 管理メニュー -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">管理メニュー</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <a href="{{ route('admin.applications.index') }}" class="block p-4 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">店舗申請管理</h4>
                                        <p class="text-sm text-gray-600">新規店舗の承認</p>
                                    </div>
                                </div>
                                @if($pendingApplications > 0)
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-red-500 rounded-full">
                                        {{ $pendingApplications }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <a href="{{ route('admin.reviews.index') }}" class="block p-4 bg-yellow-50 hover:bg-yellow-100 rounded-lg border border-yellow-200 transition">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <svg class="h-8 w-8 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"/>
                                    </svg>
                                    <div>
                                        <h4 class="font-bold text-gray-900">レビュー承認</h4>
                                        <p class="text-sm text-gray-600">レビューの承認・却下</p>
                                    </div>
                                </div>
                                @if($pendingReviews > 0)
                                    <span class="inline-flex items-center justify-center w-8 h-8 text-xs font-bold text-white bg-red-500 rounded-full">
                                        {{ $pendingReviews }}
                                    </span>
                                @endif
                            </div>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="block p-4 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">ユーザー管理</h4>
                                    <p class="text-sm text-gray-600">レベル・ステータス管理</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.categories.index') }}" class="block p-4 bg-purple-50 hover:bg-purple-100 rounded-lg border border-purple-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-purple-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">カテゴリー管理</h4>
                                    <p class="text-sm text-gray-600">業種カテゴリーの管理</p>
                                </div>
                            </div>
                        </a>

                        <a href="{{ route('admin.areas.index') }}" class="block p-4 bg-orange-50 hover:bg-orange-100 rounded-lg border border-orange-200 transition">
                            <div class="flex items-center">
                                <svg class="h-8 w-8 text-orange-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                                <div>
                                    <h4 class="font-bold text-gray-900">エリア管理</h4>
                                    <p class="text-sm text-gray-600">地域エリアの管理</p>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>
            </div>

            <!-- 最近のアクティビティ -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                <!-- 最近の店舗申請 -->
                @php
                    $recentApplications = \App\Models\StoreApplication::orderBy('created_at', 'desc')->limit(5)->get();
                @endphp
                @if($recentApplications->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">最近の店舗申請</h3>
                                <a href="{{ route('admin.applications.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    すべて見る →
                                </a>
                            </div>
                            <div class="space-y-3">
                                @foreach($recentApplications as $application)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div>
                                            <p class="font-medium text-gray-900">{{ $application->store_name }}</p>
                                            <p class="text-sm text-gray-600">{{ $application->applicant_name }}</p>
                                        </div>
                                        @if($application->status === 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                承認待ち
                                            </span>
                                        @elseif($application->status === 'approved')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                承認済み
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                却下
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif

                <!-- 最近のレビュー -->
                @php
                    $recentReviews = \App\Models\Review::with(['store', 'customer'])->orderBy('created_at', 'desc')->limit(5)->get();
                @endphp
                @if($recentReviews->count() > 0)
                    <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h3 class="text-lg font-bold text-gray-900">最近のレビュー</h3>
                                <a href="{{ route('admin.reviews.index') }}" class="text-sm text-blue-600 hover:text-blue-700 font-medium">
                                    すべて見る →
                                </a>
                            </div>
                            <div class="space-y-3">
                                @foreach($recentReviews as $review)
                                    <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                                        <div class="flex-1">
                                            <p class="font-medium text-gray-900 text-sm">{{ $review->title }}</p>
                                            <p class="text-xs text-gray-600">{{ $review->store->store_name }} - {{ $review->customer->name }}</p>
                                            <div class="flex items-center mt-1">
                                                <div class="flex text-yellow-400">
                                                    @for($i = 1; $i <= 5; $i++)
                                                        @if($i <= $review->rating)
                                                            <svg class="w-3 h-3 fill-current" viewBox="0 0 20 20">
                                                                <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                            </svg>
                                                        @endif
                                                    @endfor
                                                </div>
                                            </div>
                                        </div>
                                        @if($review->status === 'pending')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                承認待ち
                                            </span>
                                        @elseif($review->status === 'published')
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                                公開中
                                            </span>
                                        @else
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                                却下
                                            </span>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Chart.js CDN -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

    <script>
        // 新規店舗登録数のグラフ
        const storesCtx = document.getElementById('storesChart').getContext('2d');
        new Chart(storesCtx, {
            type: 'bar',
            data: {
                labels: @json($monthLabels),
                datasets: [{
                    label: '新規店舗登録数',
                    data: @json($monthlyStores),
                    backgroundColor: 'rgba(59, 130, 246, 0.8)',
                    borderColor: 'rgb(59, 130, 246)',
                    borderWidth: 2,
                    borderRadius: 5,
                    hoverBackgroundColor: 'rgba(59, 130, 246, 1)'
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + '店舗';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + '店舗';
                            }
                        }
                    }
                }
            }
        });

        // レビュー投稿数のグラフ
        const reviewsCtx = document.getElementById('reviewsChart').getContext('2d');
        new Chart(reviewsCtx, {
            type: 'line',
            data: {
                labels: @json($monthLabels),
                datasets: [{
                    label: 'レビュー投稿数',
                    data: @json($monthlyReviews),
                    backgroundColor: 'rgba(139, 92, 246, 0.1)',
                    borderColor: 'rgb(139, 92, 246)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: 'rgb(139, 92, 246)',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.dataset.label + ': ' + context.parsed.y + '件';
                            }
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            callback: function(value) {
                                return value + '件';
                            }
                        }
                    }
                }
            }
        });
    </script>
</x-app-layout>
