<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            管理者ダッシュボード
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @php
                use App\Models\Store;
                use App\Models\Customer;
                use App\Models\Review;
                use App\Models\Reservation;
                use App\Models\StoreApplication;

                $totalStores = Store::count();
                $activeStores = Store::where('status', 'active')->count();
                $totalCustomers = Customer::where('status', 'active')->count();
                $pendingApplications = StoreApplication::where('status', 'pending')->count();
                $pendingReviews = Review::where('status', 'pending')->count();
                $todayReservations = Reservation::whereDate('reservation_date', today())->count();
                $totalReviews = Review::where('status', 'published')->count();
                $avgRating = Review::where('status', 'published')->avg('rating') ?? 0;
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
                    $recentApplications = StoreApplication::orderBy('created_at', 'desc')->limit(5)->get();
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
                    $recentReviews = Review::with(['store', 'customer'])->orderBy('created_at', 'desc')->limit(5)->get();
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
</x-app-layout>
