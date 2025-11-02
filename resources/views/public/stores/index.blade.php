<x-layouts.public>
    <x-slot name="title">店舗検索</x-slot>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center text-sm text-gray-600">
                <a href="/" class="hover:text-orange-500">ホーム</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">店舗検索</span>
            </div>
        </div>
    </div>

    <div class="py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Search Form -->
            <div class="bg-white rounded-xl shadow-lg p-6 mb-6 border border-orange-100">
                <div class="flex items-center mb-4">
                    <svg class="w-5 h-5 text-orange-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                    </svg>
                    <h2 class="text-lg font-bold text-gray-900">絞り込み検索</h2>
                </div>
                <form method="GET" action="{{ route('stores.index') }}" class="space-y-4">
                    <!-- Keyword Search -->
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-2">
                            キーワード
                        </label>
                        <input type="text"
                               name="keyword"
                               id="keyword"
                               value="{{ request('keyword') }}"
                               placeholder="店舗名、説明で検索"
                               class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                カテゴリー
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">すべて</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                        {{ $category->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area Filter -->
                        <div>
                            <label for="area_id" class="block text-sm font-medium text-gray-700 mb-2">
                                エリア
                            </label>
                            <select name="area_id"
                                    id="area_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">すべて</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}" {{ request('area_id') == $area->id ? 'selected' : '' }}>
                                        {{ $area->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Rating Filter -->
                        <div>
                            <label for="min_rating" class="block text-sm font-medium text-gray-700 mb-2">
                                評価
                            </label>
                            <select name="min_rating"
                                    id="min_rating"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">すべて</option>
                                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>★4以上</option>
                                <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>★4.5以上</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                並び順
                            </label>
                            <select name="sort"
                                    id="sort"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>新着順</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>評価順</option>
                                <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>レビュー数順</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>閲覧数順</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-md transition">
                            🔍 この条件で検索
                        </button>

                        <a href="{{ route('stores.index') }}"
                           class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition">
                            ✕ クリア
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-orange-50 border-l-4 border-orange-500 px-4 py-3 rounded">
                        <p class="text-gray-900 font-bold">
                            <span class="text-orange-600 text-2xl">{{ $stores->total() }}</span>
                            <span class="text-lg ml-1">件</span>
                            <span class="text-gray-600 text-sm ml-2">の店舗が見つかりました</span>
                            @if(request('keyword') || request('category_id') || request('area_id'))
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    絞り込み中
                                </span>
                            @endif
                        </p>
                    </div>
                </div>
            </div>

            <!-- Store List -->
            @if($stores->count() > 0)
                <div class="space-y-6 mb-8">
                    @foreach($stores as $store)
                        <div class="bg-white rounded-lg shadow-md hover:shadow-xl transition-shadow duration-300 p-6 border border-gray-200 relative">
                            <!-- Featured Badge -->
                            @if($store->is_featured && $store->featured_until && $store->featured_until->isFuture())
                                <div class="absolute top-4 right-4 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded z-10">
                                    店舗公式
                                </div>
                            @endif

                            <a href="{{ route('stores.show', $store) }}" class="block">
                                <!-- Store Name -->
                                <h3 class="text-2xl font-bold text-gray-900 mb-2 pr-20">
                                    {{ $store->store_name }}
                                </h3>

                                <!-- Catchphrase -->
                                @if($store->storeDetail && $store->storeDetail->catchphrase)
                                    <p class="text-base text-gray-600 mb-4">
                                        {{ $store->storeDetail->catchphrase }}
                                    </p>
                                @endif

                                <!-- Images Grid (3 square images) -->
                                <div class="grid grid-cols-3 gap-2 mb-4">
                                    @php
                                        $images = $store->images->take(3);
                                        $imageCount = $images->count();
                                    @endphp

                                    @if($imageCount > 0)
                                        @foreach($images as $image)
                                            <div class="aspect-square bg-gray-200 rounded overflow-hidden">
                                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                                     alt="{{ $store->store_name }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                        @endforeach

                                        @for($i = $imageCount; $i < 3; $i++)
                                            <div class="aspect-square bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endfor
                                    @else
                                        @for($i = 0; $i < 3; $i++)
                                            <div class="aspect-square bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        @endfor
                                    @endif
                                </div>

                                <!-- Features Icons -->
                                <div class="flex flex-wrap gap-2 mb-4 pb-4 border-b border-gray-200">
                                    @if($store->storeDetail)
                                        @if($store->storeDetail->credit_card)
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded text-sm">
                                                💳 クレジットカード可
                                            </span>
                                        @endif
                                        @if($store->activeCoupons && $store->activeCoupons->count() > 0)
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded text-sm">
                                                🎫 クーポンあり ({{ $store->activeCoupons->count() }}件)
                                            </span>
                                        @endif
                                        @if($store->storeDetail->wifi)
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded text-sm">
                                                📶 Wi-Fi
                                            </span>
                                        @endif
                                        @if($store->storeDetail->private_rooms)
                                            <span class="inline-flex items-center px-3 py-1 bg-gray-100 rounded text-sm">
                                                🚪 個室あり
                                            </span>
                                        @endif
                                    @endif

                                    <!-- Category & Area -->
                                    @if($store->category)
                                        <span class="inline-flex items-center px-3 py-1 bg-orange-100 text-orange-700 rounded text-sm font-bold">
                                            {{ $store->category->name }}
                                        </span>
                                    @endif
                                </div>

                                <!-- Store Info -->
                                <div class="space-y-2 mb-4">
                                    <!-- Address -->
                                    @if($store->prefecture || $store->city)
                                        <p class="text-sm text-gray-600">
                                            📍 {{ $store->prefecture }}{{ $store->city }}{{ $store->street_address }}
                                        </p>
                                    @endif

                                    <!-- Today's Hours -->
                                    @php
                                        $today = now()->dayOfWeek;
                                        $todayHours = $store->businessHours->where('day_of_week', $today)->first();
                                    @endphp
                                    @if($todayHours)
                                        <p class="text-sm">
                                            <span class="font-bold text-gray-700">本日の営業状況</span>
                                            @if($todayHours->is_closed)
                                                <span class="text-red-600 font-bold ml-2">定休日</span>
                                            @else
                                                <span class="text-green-600 font-bold ml-2">
                                                    {{ substr($todayHours->open_time, 0, 5) }}〜{{ substr($todayHours->close_time, 0, 5) }}
                                                </span>
                                            @endif
                                        </p>
                                    @endif

                                    <!-- Rating -->
                                    <div class="flex items-center">
                                        <div class="flex items-center text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($store->average_rating))
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
                                        <span class="ml-2 text-sm text-gray-600">
                                            {{ number_format($store->average_rating, 1) }}
                                            <span class="text-gray-400">({{ $store->review_count }}件のレビュー)</span>
                                        </span>
                                    </div>
                                </div>

                                <!-- Menu Section -->
                                @if($store->menus && $store->menus->where('is_available', true)->count() > 0)
                                    <div class="bg-gray-50 rounded p-4">
                                        <h4 class="font-bold text-gray-900 mb-3">主なメニュー</h4>
                                        <ul class="space-y-2">
                                            @foreach($store->menus->where('is_available', true)->take(3) as $menu)
                                                <li class="flex justify-between items-center py-2 border-b border-gray-200 last:border-0">
                                                    <span class="text-sm text-gray-700">{{ $menu->name }}</span>
                                                    <span class="text-sm font-bold text-gray-900 whitespace-nowrap ml-4">
                                                        ¥{{ number_format($menu->price) }}（税込）
                                                    </span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                            </a>
                        </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $stores->links() }}
                </div>
            @else
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.172 16.172a4 4 0 015.656 0M9 10h.01M15 10h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                    <h3 class="mt-2 text-lg font-medium text-gray-900">店舗が見つかりませんでした</h3>
                    <p class="mt-1 text-sm text-gray-500">検索条件を変更してお試しください</p>
                    <div class="mt-6">
                        <a href="{{ route('stores.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            すべての店舗を表示
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>
