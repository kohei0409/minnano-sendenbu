<x-layouts.public>
    <x-slot name="title">{{ __('store.search_title') }}</x-slot>

    <!-- Breadcrumb -->
    <div class="bg-white border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-3">
            <div class="flex items-center text-sm text-gray-600">
                <a href="/" class="hover:text-orange-500">{{ __('common.home') }}</a>
                <svg class="w-4 h-4 mx-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
                <span class="text-gray-900 font-medium">{{ __('store.search_title') }}</span>
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
                    <h2 class="text-lg font-bold text-gray-900">{{ __('store.search_filters') }}</h2>
                </div>
                <form method="GET" action="{{ route('stores.index') }}" class="space-y-4">
                    <!-- Keyword Search -->
                    <div>
                        <label for="keyword" class="block text-sm font-medium text-gray-700 mb-2">
                            {{ __('store.search_keyword') }}
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            <input type="text"
                                   name="keyword"
                                   id="keyword"
                                   value="{{ request('keyword') }}"
                                   placeholder="{{ __('store.search_keyword_placeholder') }}"
                                   class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Category Filter -->
                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('store.filter_category') }}
                            </label>
                            <select name="category_id"
                                    id="category_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('store.filter_all') }}</option>
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
                                {{ __('store.filter_area') }}
                            </label>
                            <select name="area_id"
                                    id="area_id"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('store.filter_all') }}</option>
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
                                {{ __('store.filter_rating') }}
                            </label>
                            <select name="min_rating"
                                    id="min_rating"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="">{{ __('store.filter_all') }}</option>
                                <option value="4" {{ request('min_rating') == '4' ? 'selected' : '' }}>{{ __('store.rating_4_above') }}</option>
                                <option value="4.5" {{ request('min_rating') == '4.5' ? 'selected' : '' }}>{{ __('store.rating_4_5_above') }}</option>
                            </select>
                        </div>
                    </div>

                    <!-- Sort Options -->
                    <div class="flex flex-wrap gap-4 items-end">
                        <div class="flex-1">
                            <label for="sort" class="block text-sm font-medium text-gray-700 mb-2">
                                {{ __('store.sort_by') }}
                            </label>
                            <select name="sort"
                                    id="sort"
                                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="created_at" {{ request('sort') == 'created_at' ? 'selected' : '' }}>{{ __('store.sort_newest') }}</option>
                                <option value="rating" {{ request('sort') == 'rating' ? 'selected' : '' }}>{{ __('store.sort_rating') }}</option>
                                <option value="reviews" {{ request('sort') == 'reviews' ? 'selected' : '' }}>{{ __('store.sort_reviews') }}</option>
                                <option value="views" {{ request('sort') == 'views' ? 'selected' : '' }}>{{ __('store.sort_views') }}</option>
                            </select>
                        </div>

                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-lg hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-orange-500 focus:ring-offset-2 shadow-md transition inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                            {{ __('store.search_button') }}
                        </button>

                        <a href="{{ route('stores.index') }}"
                           class="px-8 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-400 focus:ring-offset-2 transition inline-flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                            {{ __('common.clear') }}
                        </a>
                    </div>
                </form>
            </div>

            <!-- Results Count -->
            <div class="mb-6 flex items-center justify-between">
                <div class="flex items-center">
                    <div class="bg-orange-50 border-l-4 border-orange-500 px-4 py-3 rounded">
                        <p class="text-gray-900 font-bold">
                            {{ __('store.results_found', ['count' => $stores->total()]) }}
                            @if(request('keyword') || request('category_id') || request('area_id'))
                                <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                                    {{ __('store.results_with_filters') }}
                                </span>
                            @endif
                        </p>
                    </div>
                </div>

                <!-- Map Toggle Button -->
                @if($stores->count() > 0)
                    <button id="mapToggleBtn"
                            onclick="toggleMap()"
                            class="inline-flex items-center px-4 py-2 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 20l-5.447-2.724A1 1 0 013 16.382V5.618a1 1 0 011.447-.894L9 7m0 13l6-3m-6 3V7m6 10l4.553 2.276A1 1 0 0021 18.382V7.618a1 1 0 00-.553-.894L15 4m0 13V4m0 0L9 7" />
                        </svg>
                        <span id="mapToggleText">{{ __('store.show_map') }}</span>
                    </button>
                @endif
            </div>

            <!-- Map Area (Collapsible) -->
            @if($stores->count() > 0)
                <div id="mapArea" class="mb-6 hidden">
                    <div class="bg-white rounded-lg border border-gray-200 p-6">
                        <h2 class="text-xl font-bold text-gray-900 mb-4">検索結果の地図</h2>

                        <!-- Map Container -->
                        <div id="storeListMap" class="w-full h-96 rounded-lg bg-gray-100 flex items-center justify-center">
                            <div class="text-center">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-3"></div>
                                <p class="text-gray-600">地図を読み込んでいます...</p>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

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
                    <h3 class="mt-2 text-lg font-medium text-gray-900">{{ __('store.no_stores_found') }}</h3>
                    <p class="mt-1 text-sm text-gray-500">{{ __('store.change_conditions') }}</p>
                    <div class="mt-6">
                        <a href="{{ route('stores.index') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700">
                            {{ __('store.show_all_stores') }}
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- Google Maps API Script for Store List -->
    @if($stores->count() > 0)
        @php
            $apiKey = env('GOOGLE_MAPS_API_KEY', '');
        @endphp
        @if($apiKey)
            <script>
                (g=>{var h,a,k,p="The Google Maps JavaScript API",c="google",l="importLibrary",q="__ib__",m=document,b=window;b=b[c]||(b[c]={});var d=b.maps||(b.maps={}),r=new Set,e=new URLSearchParams,u=()=>h||(h=new Promise(async(f,n)=>{await (a=m.createElement("script"));e.set("libraries",[...r]+"");for(k in g)e.set(k.replace(/[A-Z]/g,t=>"_"+t[0].toLowerCase()),g[k]);e.set("callback",c+".maps."+q);a.src=`https://maps.googleapis.com/maps/api/js?`+e;d[q]=f;a.onerror=()=>h=n(Error(p+" could not load."));a.nonce=m.querySelector("script[nonce]")?.nonce||"";m.head.append(a)}));d[l]?console.warn(p+" only loads once. Ignoring:",g):d[l]=(f,...n)=>r.add(f)&&u().then(()=>d[l](f,...n))})({
                    key: "{{ $apiKey }}",
                    v: "weekly"
                });
            </script>

            <script>
                let listMap;
                let markers = [];
                let infoWindows = [];
                let mapInitialized = false;

                // 店舗データを準備
                const stores = [
                    @foreach($stores as $store)
                        @if($store->latitude && $store->longitude)
                            {
                                id: {{ $store->id }},
                                name: "{{ addslashes($store->store_name) }}",
                                lat: {{ $store->latitude }},
                                lng: {{ $store->longitude }},
                                address: "{{ addslashes($store->prefecture . $store->city . $store->street_address) }}",
                                url: "{{ route('stores.show', $store) }}"
                            },
                        @endif
                    @endforeach
                ];

                // 地図の表示/非表示を切り替え
                function toggleMap() {
                    const mapArea = document.getElementById('mapArea');
                    const toggleText = document.getElementById('mapToggleText');

                    if (mapArea.classList.contains('hidden')) {
                        mapArea.classList.remove('hidden');
                        toggleText.textContent = '{{ __('store.hide_map') }}';

                        // 地図がまだ初期化されていない場合は初期化
                        if (!mapInitialized && stores.length > 0) {
                            initStoreListMap();
                        }
                    } else {
                        mapArea.classList.add('hidden');
                        toggleText.textContent = '{{ __('store.show_map') }}';
                    }
                }

                // 店舗一覧の地図を初期化
                async function initStoreListMap() {
                    if (stores.length === 0) {
                        document.getElementById('storeListMap').innerHTML = `
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <p>位置情報を持つ店舗がありません</p>
                                </div>
                            </div>
                        `;
                        return;
                    }

                    try {
                        const { Map } = await google.maps.importLibrary("maps");
                        const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                        // 地図の中心を計算（全店舗の平均位置）
                        const centerLat = stores.reduce((sum, store) => sum + store.lat, 0) / stores.length;
                        const centerLng = stores.reduce((sum, store) => sum + store.lng, 0) / stores.length;

                        // 地図を作成
                        listMap = new Map(document.getElementById("storeListMap"), {
                            zoom: 12,
                            center: { lat: centerLat, lng: centerLng },
                            mapId: "STORE_LIST_MAP"
                        });

                        // 各店舗にマーカーを配置
                        stores.forEach((store, index) => {
                            const position = { lat: store.lat, lng: store.lng };

                            const marker = new AdvancedMarkerElement({
                                map: listMap,
                                position: position,
                                title: store.name
                            });

                            const infoWindow = new google.maps.InfoWindow({
                                content: `
                                    <div style="padding: 10px; max-width: 250px;">
                                        <h3 style="margin: 0 0 8px 0; font-size: 14px; font-weight: bold; color: #111;">
                                            ${store.name}
                                        </h3>
                                        <p style="margin: 0 0 10px 0; font-size: 12px; color: #666;">
                                            ${store.address}
                                        </p>
                                        <a href="${store.url}"
                                           style="display: inline-block; padding: 6px 12px; background: #f97316; color: white; text-decoration: none; border-radius: 6px; font-size: 12px; font-weight: bold;">
                                            詳細を見る
                                        </a>
                                    </div>
                                `
                            });

                            marker.addListener("click", () => {
                                // 他の情報ウィンドウを閉じる
                                infoWindows.forEach(iw => iw.close());
                                infoWindow.open(listMap, marker);
                            });

                            markers.push(marker);
                            infoWindows.push(infoWindow);
                        });

                        // 全てのマーカーが見えるように地図を調整
                        if (stores.length > 1) {
                            const bounds = new google.maps.LatLngBounds();
                            stores.forEach(store => {
                                bounds.extend({ lat: store.lat, lng: store.lng });
                            });
                            listMap.fitBounds(bounds);
                        }

                        mapInitialized = true;
                    } catch (error) {
                        console.error('Error initializing map:', error);
                        document.getElementById('storeListMap').innerHTML = `
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p>地図を表示できませんでした</p>
                                </div>
                            </div>
                        `;
                    }
                }
            </script>
        @else
            <script>
                function toggleMap() {
                    const mapArea = document.getElementById('mapArea');
                    const toggleText = document.getElementById('mapToggleText');

                    if (mapArea.classList.contains('hidden')) {
                        mapArea.classList.remove('hidden');
                        toggleText.textContent = '{{ __('store.hide_map') }}';

                        document.getElementById('storeListMap').innerHTML = `
                            <div class="flex items-center justify-center h-full">
                                <div class="text-center text-gray-500">
                                    <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                                    </svg>
                                    <p>Google Maps APIキーが設定されていません</p>
                                    <p class="text-sm mt-2">詳しくはGOOGLE_MAPS_SETUP.mdをご覧ください</p>
                                </div>
                            </div>
                        `;
                    } else {
                        mapArea.classList.add('hidden');
                        toggleText.textContent = '{{ __('store.show_map') }}';
                    }
                }
            </script>
        @endif
    @endif
</x-layouts.public>
