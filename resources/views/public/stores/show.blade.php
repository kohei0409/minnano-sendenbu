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
                <h1 class="text-3xl font-bold text-gray-900 mb-3">{{ $store->store_name }}</h1>
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
                        電話で予約・問い合わせ
                    </a>
                @endif
                <a href="{{ route('reservations.create', $store) }}"
                   class="inline-flex items-center px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                    </svg>
                    ネット予約
                </a>
            </div>

            <!-- Features Section -->
            @if($store->storeDetail)
                <div class="bg-gray-50 rounded-lg p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">特徴</h2>

                    <!-- Amenities -->
                    <div class="mb-6">
                        <h3 class="text-lg font-bold text-gray-900 mb-3">設備・サービス</h3>
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
                            <h3 class="text-lg font-bold text-gray-900 mb-2">アクセス</h3>
                            <p class="text-gray-700">{{ $store->storeDetail->access_info }}</p>
                        </div>
                    @endif
                </div>
            @endif

            <!-- Business Hours Table -->
            @if($store->businessHours->count() > 0)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-4">営業時間</h2>

                    <!-- Today's Status -->
                    @php
                        $today = now()->dayOfWeek;
                        $todayHours = $store->businessHours->where('day_of_week', $today)->first();
                    @endphp
                    @if($todayHours)
                        <div class="flex items-center gap-3 p-4 bg-green-50 rounded-lg mb-6">
                            <span class="font-bold text-gray-900">本日の営業状況</span>
                            @if($todayHours->is_closed)
                                <span class="text-red-600 font-bold">定休日</span>
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
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">メニュー</h2>

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
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">クーポン</h2>
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
                    <h2 class="text-2xl font-bold text-gray-900">口コミ（{{ $store->publishedReviews->count() }}件）</h2>
                    @auth('customer')
                        <a href="{{ route('customer.reviews.create', $store) }}"
                           class="px-6 py-2 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 transition">
                            口コミを書く
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
                                すべての口コミを見る（{{ $store->publishedReviews->count() }}件）
                            </a>
                        </div>
                    @endif
                @else
                    <p class="text-center text-gray-500 py-8">まだ口コミがありません</p>
                @endif
            </div>

            <!-- Photo Gallery -->
            @if($store->images->count() > 1)
                <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">写真ギャラリー</h2>
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

            <!-- Store Overview -->
            <div class="bg-white rounded-lg border border-gray-200 p-6 mb-8">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">概要</h2>

                <dl class="divide-y divide-gray-200">
                    <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                        <dt class="font-bold text-gray-900 md:col-span-1">店舗名</dt>
                        <dd class="text-gray-700 md:col-span-2">{{ $store->store_name }}</dd>
                    </div>

                    @if($store->category)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">ジャンル</dt>
                            <dd class="text-gray-700 md:col-span-2">{{ $store->category->name }}</dd>
                        </div>
                    @endif

                    @if($store->phone)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">電話番号</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                <a href="tel:{{ $store->phone }}" class="text-blue-600 hover:underline">{{ $store->phone }}</a>
                            </dd>
                        </div>
                    @endif

                    @if($store->email)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">メール</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                <a href="mailto:{{ $store->email }}" class="text-blue-600 hover:underline">{{ $store->email }}</a>
                            </dd>
                        </div>
                    @endif

                    @if($store->prefecture || $store->city)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">住所</dt>
                            <dd class="text-gray-700 md:col-span-2">
                                @if($store->postal_code)〒{{ $store->postal_code }}<br>@endif
                                {{ $store->prefecture }}{{ $store->city }}{{ $store->street_address }}
                                @if($store->building)<br>{{ $store->building }}@endif
                            </dd>
                        </div>
                    @endif

                    @if($store->storeDetail && $store->storeDetail->access_info)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">アクセス</dt>
                            <dd class="text-gray-700 md:col-span-2">{{ $store->storeDetail->access_info }}</dd>
                        </div>
                    @endif

                    @if($store->storeDetail && $store->storeDetail->payment_methods)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">支払い方法</dt>
                            <dd class="text-gray-700 md:col-span-2">{{ $store->storeDetail->payment_methods }}</dd>
                        </div>
                    @endif

                    @if($store->storeDetail && $store->storeDetail->credit_card)
                        <div class="py-4 grid grid-cols-1 md:grid-cols-3 gap-4">
                            <dt class="font-bold text-gray-900 md:col-span-1">クレジットカード</dt>
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
            fetch(`/customer/stores/${storeId}/favorite`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                alert(data.message);
                location.reload();
            });
        }
    </script>
    @endauth
</x-layouts.public>
