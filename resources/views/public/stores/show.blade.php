<x-layouts.public>
    <x-slot name="title">{{ $store->store_name }}</x-slot>

    <!-- Image Slider (3:1 ratio, wide) -->
    @if($store->images->count() > 0)
        <div class="w-full bg-gray-900">
            <div class="max-w-7xl mx-auto">
                <div class="relative overflow-hidden" style="aspect-ratio: 3/1;">
                    @php
                        $mainImage = $store->images->first();
                    @endphp
                    <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                         alt="{{ $store->store_name }}"
                         class="w-full h-full object-cover">
                </div>
            </div>
        </div>
    @endif

    <!-- Main Content -->
    <div class="bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

            <!-- Store Name & Catchphrase -->
            <div class="mb-8">
                <div class="flex items-start justify-between mb-3">
                    <h1 class="text-3xl font-bold text-gray-900">{{ $store->store_name }}</h1>

                    <!-- お気に入りボタン -->
                    @auth('customer')
                        @php
                            $isFavorited = auth('customer')->user()->favorites()->where('store_id', $store->id)->exists();
                        @endphp
                        <button onclick="toggleFavorite({{ $store->id }})"
                                id="favorite-btn-{{ $store->id }}"
                                class="flex items-center gap-2 px-4 py-2 rounded-lg font-bold transition {{ $isFavorited ? 'bg-red-100 text-red-600 hover:bg-red-200' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }}">
                            <svg class="w-6 h-6" fill="{{ $isFavorited ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                            </svg>
                            <span id="favorite-text-{{ $store->id }}">{{ $isFavorited ? __('store.added_favorite') : __('store.add_favorite') }}</span>
                        </button>
                    @endauth
                </div>

                @if($store->storeDetail && $store->storeDetail->catchphrase)
                    <p class="text-xl font-bold text-red-600 mb-4">{{ $store->storeDetail->catchphrase }}</p>
                @endif

                <!-- Category & Tags -->
                <div class="flex flex-wrap gap-2 mb-4">
                    @if($store->category)
                        <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded text-sm font-bold">
                            {{ $store->category->name }}
                        </span>
                    @endif
                    @if($store->area)
                        <span class="px-3 py-1 bg-blue-50 text-blue-700 rounded text-sm">
                            📍 {{ $store->area->name }}
                        </span>
                    @endif
                </div>
            </div>

            <!-- Store Description -->
            @if($store->storeDetail && $store->storeDetail->description)
                <div class="mb-8">
                    <div class="prose max-w-none">
                        @foreach(explode("\n\n", $store->storeDetail->description) as $paragraph)
                            <p class="text-gray-700 leading-relaxed mb-4">{{ $paragraph }}</p>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Contact Buttons -->
            <div class="mb-12 flex flex-wrap gap-4">
                @if($store->phone)
                    <a href="tel:{{ $store->phone }}"
                       class="inline-flex items-center px-6 py-3 bg-green-600 text-white font-bold rounded-lg hover:bg-green-700 transition">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ __('store.phone_reserve') }}
                    </a>
                @endif
                <a href="{{ route('reservations.create', $store) }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    {{ __('store.online_reserve') }}
                </a>
            </div>

            <!-- Features Section -->
            @if($store->storeDetail)
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('store.features') }}</h2>

                    <!-- Amenities -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">{{ __('store.amenities') }}</h3>
                        <div class="flex flex-wrap gap-3">
                            @if($store->storeDetail->wifi)
                                <span class="px-4 py-2 bg-white rounded-lg border border-gray-200 text-sm">📶 Wi-Fi</span>
                            @endif
                            @if($store->storeDetail->power_outlet)
                                <span class="px-4 py-2 bg-white rounded-lg border border-gray-200 text-sm">🔌 電源コンセント</span>
                            @endif
                            @if($store->storeDetail->credit_card)
                                <span class="px-4 py-2 bg-white rounded-lg border border-gray-200 text-sm">💳 クレジットカード可</span>
                            @endif
                            @if($store->storeDetail->private_rooms)
                                <span class="px-4 py-2 bg-white rounded-lg border border-gray-200 text-sm">🚪 個室あり</span>
                            @endif
                            @if($store->storeDetail->seats)
                                <span class="px-4 py-2 bg-white rounded-lg border border-gray-200 text-sm">💺 座席: {{ $store->storeDetail->seats }}席</span>
                            @endif
                        </div>
                    </div>

                    @if($store->storeDetail->access_info)
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-2">{{ __('store.access') }}</h3>
                            <p class="text-gray-700">{{ $store->storeDetail->access_info }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Business Hours Table -->
            @if($store->businessHours->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">{{ __('store.business_hours') }}</h2>

                    <!-- Today's Status -->
                    @php
                        $today = now()->dayOfWeek;
                        $todayHours = $store->businessHours->where('day_of_week', $today)->first();
                    @endphp
                    @if($todayHours)
                        <div class="flex items-center gap-3 p-4 bg-green-50 rounded-lg mb-6">
                            <span class="font-bold text-gray-900">{{ __('store.today_hours') }}</span>
                            @if($todayHours->is_closed)
                                <span class="text-red-600 font-bold">{{ __('store.closed') }}</span>
                            @else
                                <span class="text-green-600 font-bold text-lg">
                                    {{ substr($todayHours->open_time, 0, 5) }}〜{{ substr($todayHours->close_time, 0, 5) }}
                                </span>
                            @endif
                        </div>
                    @endif

                    <!-- Hours Table -->
                    <div class="overflow-x-auto">
                        <table class="w-full border-collapse">
                            <thead>
                                <tr class="bg-gray-100">
                                    @php
                                        $days = ['日', '月', '火', '水', '木', '金', '土'];
                                    @endphp
                                    @foreach($days as $day)
                                        <th class="border border-gray-300 px-4 py-3 text-center font-bold">{{ $day }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    @for($i = 0; $i < 7; $i++)
                                        @php
                                            $hours = $store->businessHours->where('day_of_week', $i)->first();
                                        @endphp
                                        <td class="border border-gray-300 px-4 py-3 text-center text-sm">
                                            @if($hours)
                                                @if($hours->is_closed)
                                                    <span class="text-red-600">定休日</span>
                                                @else
                                                    {{ substr($hours->open_time, 0, 5) }}<br>〜<br>{{ substr($hours->close_time, 0, 5) }}
                                                @endif
                                            @else
                                                -
                                            @endif
                                        </td>
                                    @endfor
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            @endif

            <!-- Menus -->
            @if($store->menus && $store->menus->where('is_available', true)->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('store.main_menu') }}</h2>

                    @php
                        $menusByCategory = $store->menus->where('is_available', true)->groupBy('category');
                    @endphp

                    @foreach($menusByCategory as $category => $menus)
                        @if($category)
                            <h3 class="text-lg font-bold text-gray-900 mb-3 mt-6 first:mt-0">{{ $category }}</h3>
                        @endif
                        <div class="space-y-0">
                            @foreach($menus as $menu)
                                <div class="flex justify-between items-start py-4 border-b border-gray-200 last:border-0">
                                    <div class="flex-1">
                                        <h4 class="font-semibold text-gray-900 mb-1">{{ $menu->name }}</h4>
                                        @if($menu->description)
                                            <p class="text-sm text-gray-600">{{ $menu->description }}</p>
                                        @endif
                                    </div>
                                    <div class="ml-6 text-right flex-shrink-0">
                                        <span class="text-lg font-bold text-gray-900">¥{{ number_format($menu->price) }}</span>
                                        <span class="text-sm text-gray-600">（税込）</span>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endforeach
                </div>
            @endif

            <!-- Active Coupons -->
            @if($store->activeCoupons && $store->activeCoupons->count() > 0)
                <div class="mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('store.coupons') }}</h2>
                    <div class="space-y-4">
                        @foreach($store->activeCoupons as $coupon)
                            <div class="border-2 border-dashed border-orange-400 rounded-lg p-6 bg-gradient-to-r from-orange-50 to-yellow-50">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="inline-block bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full mb-3">
                                            🎫 クーポン
                                        </div>
                                        <h3 class="text-xl font-bold text-gray-900 mb-2">{{ $coupon->title }}</h3>
                                        @if($coupon->description)
                                            <p class="text-gray-700 mb-3">{{ $coupon->description }}</p>
                                        @endif
                                        <div class="flex items-center gap-4 text-sm text-gray-600">
                                            <span>📅 有効期限: {{ $coupon->start_date->format('Y/m/d') }} 〜 {{ $coupon->end_date->format('Y/m/d') }}</span>
                                            @if($coupon->usage_limit)
                                                <span class="bg-orange-200 text-orange-800 px-2 py-1 rounded font-semibold">
                                                    残り {{ $coupon->usage_limit - $coupon->used_count }}枚
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="ml-6 text-center bg-white rounded-lg px-5 py-3 border-2 border-orange-500 shadow-sm">
                                        <div class="text-xs text-gray-600 mb-1">クーポンコード</div>
                                        <div class="text-2xl font-bold text-orange-600">{{ $coupon->code }}</div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Reviews Section -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('review.reviews_count', ['count' => $store->publishedReviews->count()]) }}</h2>
                    @auth('customer')
                        <a href="{{ route('customer.reviews.create', $store) }}"
                           class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                            {{ __('review.write_review') }}
                        </a>
                    @endauth
                </div>

                @if($store->publishedReviews->count() > 0)
                    <div class="space-y-6">
                        @foreach($store->publishedReviews->take(5) as $review)
                            <div class="border-b border-gray-200 pb-6 last:border-0">
                                <!-- Review Title & Date -->
                                <h3 class="text-lg font-bold text-gray-900 mb-2">{{ $review->title }}</h3>
                                <p class="text-sm text-gray-500 mb-3">
                                    投稿日: {{ $review->published_at->format('Y年m月d日') }}
                                </p>

                                <!-- Rating -->
                                <div class="flex items-center mb-3">
                                    <div class="flex text-yellow-400">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $review->rating)
                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                </svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>

                                <!-- Review Content -->
                                <p class="text-gray-700 leading-relaxed mb-3">{{ $review->content }}</p>

                                <!-- Review Images -->
                                @if($review->images && $review->images->count() > 0)
                                    <div class="flex gap-2 mb-3">
                                        @foreach($review->images->take(3) as $image)
                                            <img src="{{ asset('storage/' . $image->image_path) }}"
                                                 alt="口コミ画像"
                                                 class="w-24 h-24 object-cover rounded">
                                        @endforeach
                                    </div>
                                @endif

                                <!-- Store Reply -->
                                @if($review->reply)
                                    <div class="mt-4 ml-8 bg-blue-50 rounded-lg p-4 border-l-4 border-blue-500">
                                        <div class="text-sm font-bold text-gray-900 mb-2">📝 店舗からの返信</div>
                                        <p class="text-sm text-gray-700">{{ $review->reply->content }}</p>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>

                    @if($store->publishedReviews->count() > 5)
                        <div class="mt-6 text-center">
                            <a href="#" class="inline-block px-6 py-3 bg-gray-100 text-gray-700 font-semibold rounded-lg hover:bg-gray-200 transition">
                                {{ __('review.view_all_reviews', ['count' => $store->publishedReviews->count()]) }}
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-center text-gray-500 py-8">{{ __('review.no_reviews') }}</p>
                @endif
            </div>

            <!-- Photo Gallery -->
            @if($store->images->count() > 1)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('store.photo_gallery') }}</h2>
                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($store->images as $image)
                            <div class="aspect-square bg-gray-200 rounded-lg overflow-hidden hover:opacity-90 transition cursor-pointer">
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="{{ $image->caption }}"
                                     class="w-full h-full object-cover">
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Map Section -->
            @if($store->latitude && $store->longitude || $store->prefecture && $store->city && $store->street_address)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('store.map') }}</h2>

                    <!-- Map Container -->
                    <div id="map" class="w-full h-96 rounded-lg mb-4 bg-gray-100 flex items-center justify-center">
                        <div class="text-center">
                            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-orange-500 mx-auto mb-3"></div>
                            <p class="text-gray-600">地図を読み込んでいます...</p>
                        </div>
                    </div>

                    <!-- Google Maps Link -->
                    <div class="flex justify-center">
                        <a href="https://www.google.com/maps/search/?api=1&query={{ urlencode($store->prefecture . $store->city . $store->street_address . ($store->building ?? '')) }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="inline-flex items-center px-6 py-3 bg-white border-2 border-gray-300 text-gray-700 font-semibold rounded-lg hover:bg-gray-50 transition">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                            </svg>
                            Google Mapsで開く
                        </a>
                    </div>
                </div>
            @endif

            <!-- Store Overview -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">{{ __('store.store_overview') }}</h2>

                <dl class="divide-y divide-gray-200">
                    <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <dt class="font-bold text-gray-900 md:col-span-1">{{ __('store.store_name') }}</dt>
                        <dd class="text-gray-700 md:col-span-2">{{ $store->store_name }}</dd>
                    </div>

                    @if($store->category)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('store.genre') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">{{ $store->category->name }}</dd>
                        </div>
                    @endif

                    @if($store->phone)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('common.phone') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                <a href="tel:{{ $store->phone }}" class="text-blue-600 hover:underline">{{ $store->phone }}</a>
                            </dd>
                        </div>
                    @endif

                    @if($store->email)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('common.email') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                <a href="mailto:{{ $store->email }}" class="text-blue-600 hover:underline">{{ $store->email }}</a>
                            </dd>
                        </div>
                    @endif

                    @if($store->prefecture || $store->city)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('common.address') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                @if($store->postal_code)〒{{ $store->postal_code }}<br>@endif
                                {{ $store->prefecture }}{{ $store->city }}{{ $store->street_address }}
                                @if($store->building)<br>{{ $store->building }}@endif
                            </dd>
                        </div>
                    @endif

                    @if($store->storeDetail && $store->storeDetail->access_info)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('store.access') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">{{ $store->storeDetail->access_info }}</dd>
                        </div>
                    @endif

                    @if($store->storeDetail && $store->storeDetail->payment_methods)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('store.payment_methods') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">{{ $store->storeDetail->payment_methods }}</dd>
                        </div>
                    @endif

                    @if($store->storeDetail && $store->storeDetail->credit_card)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">{{ __('store.credit_card') }}</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                <div class="flex gap-2 items-center">
                                    <span>VISA</span>
                                    <span>Mastercard</span>
                                    <span>JCB</span>
                                    <span>AMEX</span>
                                    <span>Diners</span>
                                </div>
                            </dd>
                        </div>
                    @endif
                </dl>
            </div>

        </div>
    </div>

    @auth('customer')
    <script>
        function toggleFavorite(storeId) {
            const btn = document.getElementById(`favorite-btn-${storeId}`);
            const text = document.getElementById(`favorite-text-${storeId}`);
            const svg = btn.querySelector('svg');

            fetch(`/customer/stores/${storeId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.status === 'added') {
                    // お気に入りに追加された
                    btn.classList.remove('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                    btn.classList.add('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                    svg.setAttribute('fill', 'currentColor');
                    text.textContent = 'お気に入り済み';
                } else {
                    // お気に入りから削除された
                    btn.classList.remove('bg-red-100', 'text-red-600', 'hover:bg-red-200');
                    btn.classList.add('bg-gray-100', 'text-gray-600', 'hover:bg-gray-200');
                    svg.setAttribute('fill', 'none');
                    text.textContent = 'お気に入り';
                }

                // 簡単な通知
                const notification = document.createElement('div');
                notification.className = 'fixed top-4 right-4 bg-white shadow-lg rounded-lg p-4 z-50 border-l-4 ' +
                    (data.status === 'added' ? 'border-green-500' : 'border-gray-500');
                notification.innerHTML = `
                    <p class="font-medium">${data.message}</p>
                `;
                document.body.appendChild(notification);

                setTimeout(() => {
                    notification.style.transition = 'opacity 0.3s';
                    notification.style.opacity = '0';
                    setTimeout(() => notification.remove(), 300);
                }, 2000);
            })
            .catch(error => {
                console.error('Error:', error);
                alert('エラーが発生しました');
            });
        }
    </script>
    @endauth

    <!-- Google Maps API Script -->
    @if($store->latitude && $store->longitude || $store->prefecture && $store->city && $store->street_address)
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
                let map;
                let marker;
                let infoWindow;

                async function initMap() {
                    // Google Maps API のロード
                    const { Map } = await google.maps.importLibrary("maps");
                    const { AdvancedMarkerElement } = await google.maps.importLibrary("marker");

                    @if($store->latitude && $store->longitude)
                        // 緯度経度が設定されている場合
                        const position = {
                            lat: {{ $store->latitude }},
                            lng: {{ $store->longitude }}
                        };

                        map = new Map(document.getElementById("map"), {
                            zoom: 16,
                            center: position,
                            mapId: "STORE_MAP"
                        });

                        marker = new AdvancedMarkerElement({
                            map: map,
                            position: position,
                            title: "{{ $store->store_name }}"
                        });

                        // 情報ウィンドウ
                        const contentString = `
                            <div style="padding: 10px; max-width: 300px;">
                                <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: #111;">
                                    {{ $store->store_name }}
                                </h3>
                                <p style="margin: 0; font-size: 14px; color: #666;">
                                    {{ $store->prefecture }}{{ $store->city }}{{ $store->street_address }}
                                    @if($store->building)
                                        <br>{{ $store->building }}
                                    @endif
                                </p>
                            </div>
                        `;

                        infoWindow = new google.maps.InfoWindow({
                            content: contentString
                        });

                        // マーカークリックで情報ウィンドウを表示
                        marker.addListener("click", () => {
                            infoWindow.open(map, marker);
                        });

                        // デフォルトで情報ウィンドウを表示
                        infoWindow.open(map, marker);
                    @else
                        // 緯度経度がない場合はGeocoding APIで住所から取得
                        const address = "{{ $store->prefecture }}{{ $store->city }}{{ $store->street_address }}{{ $store->building ?? '' }}";
                        const geocoder = new google.maps.Geocoder();

                        geocoder.geocode({ address: address, region: 'JP' }, (results, status) => {
                            if (status === 'OK' && results[0]) {
                                const position = results[0].geometry.location;

                                map = new Map(document.getElementById("map"), {
                                    zoom: 16,
                                    center: position,
                                    mapId: "STORE_MAP"
                                });

                                marker = new AdvancedMarkerElement({
                                    map: map,
                                    position: position,
                                    title: "{{ $store->store_name }}"
                                });

                                const contentString = `
                                    <div style="padding: 10px; max-width: 300px;">
                                        <h3 style="margin: 0 0 8px 0; font-size: 16px; font-weight: bold; color: #111;">
                                            {{ $store->store_name }}
                                        </h3>
                                        <p style="margin: 0; font-size: 14px; color: #666;">
                                            {{ $store->prefecture }}{{ $store->city }}{{ $store->street_address }}
                                            @if($store->building)
                                                <br>{{ $store->building }}
                                            @endif
                                        </p>
                                    </div>
                                `;

                                infoWindow = new google.maps.InfoWindow({
                                    content: contentString
                                });

                                marker.addListener("click", () => {
                                    infoWindow.open(map, marker);
                                });

                                infoWindow.open(map, marker);
                            } else {
                                // ジオコーディング失敗時
                                document.getElementById('map').innerHTML = `
                                    <div class="flex items-center justify-center h-full">
                                        <div class="text-center text-gray-500">
                                            <svg class="w-12 h-12 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                            </svg>
                                            <p>地図を表示できませんでした</p>
                                        </div>
                                    </div>
                                `;
                            }
                        });
                    @endif
                }

                // ページ読み込み後に地図を初期化
                initMap();
            </script>
        @else
            <script>
                // APIキーが設定されていない場合
                document.addEventListener('DOMContentLoaded', function() {
                    document.getElementById('map').innerHTML = `
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
                });
            </script>
        @endif
    @endif
</x-layouts.public>
