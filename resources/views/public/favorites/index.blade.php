<x-layouts.public>
    <x-slot name="title">お気に入り店舗</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- ヘッダー -->
            <div class="mb-8">
                <h1 class="text-3xl font-bold text-gray-900 mb-2">お気に入り店舗</h1>
                <p class="text-gray-600">あなたがお気に入りに追加した店舗一覧（{{ $favorites->total() }}件）</p>
            </div>

            @if($favorites->count() > 0)
                <!-- 店舗一覧 -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                    @foreach($favorites as $store)
                        <div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition">
                            <!-- 店舗画像 -->
                            <a href="{{ route('stores.show', $store) }}" class="block">
                                @php
                                    $mainImage = $store->images()->where('is_main', true)->first() ?? $store->images()->first();
                                @endphp
                                @if($mainImage)
                                    <div class="aspect-[4/3] overflow-hidden">
                                        <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                                             alt="{{ $store->store_name }}"
                                             class="w-full h-full object-cover hover:scale-105 transition duration-300">
                                    </div>
                                @else
                                    <div class="aspect-[4/3] bg-gray-200 flex items-center justify-center">
                                        <svg class="w-20 h-20 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            <!-- 店舗情報 -->
                            <div class="p-4">
                                <!-- カテゴリとエリア -->
                                <div class="flex flex-wrap gap-2 mb-2">
                                    @if($store->category)
                                        <span class="px-2 py-1 bg-orange-100 text-orange-700 rounded text-xs font-bold">
                                            {{ $store->category->name }}
                                        </span>
                                    @endif
                                    @if($store->area)
                                        <span class="px-2 py-1 bg-blue-50 text-blue-700 rounded text-xs">
                                            {{ $store->area->name }}
                                        </span>
                                    @endif
                                </div>

                                <!-- 店舗名 -->
                                <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-2">
                                    <a href="{{ route('stores.show', $store) }}" class="hover:text-orange-600 transition">
                                        {{ $store->store_name }}
                                    </a>
                                </h3>

                                <!-- 評価 -->
                                @if($store->publishedReviews && $store->publishedReviews->count() > 0)
                                    <div class="flex items-center gap-2 mb-2">
                                        <div class="flex text-yellow-400">
                                            @php
                                                $avgRating = $store->publishedReviews->avg('rating');
                                            @endphp
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= floor($avgRating))
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
                                        <span class="text-sm text-gray-600">
                                            {{ number_format($avgRating, 1) }} ({{ $store->publishedReviews->count() }}件)
                                        </span>
                                    </div>
                                @endif

                                <!-- 住所 -->
                                <p class="text-sm text-gray-600 mb-3">
                                    {{ $store->prefecture }}{{ $store->city }}
                                </p>

                                <!-- アクションボタン -->
                                <div class="flex items-center justify-between gap-2">
                                    <a href="{{ route('stores.show', $store) }}"
                                       class="flex-1 px-4 py-2 bg-orange-500 text-white text-center font-bold rounded hover:bg-orange-600 transition">
                                        詳細を見る
                                    </a>
                                    <button onclick="toggleFavorite({{ $store->id }})"
                                            class="px-4 py-2 bg-red-100 text-red-600 font-bold rounded hover:bg-red-200 transition">
                                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z" clip-rule="evenodd"/>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ページネーション -->
                <div class="mt-8">
                    {{ $favorites->links() }}
                </div>
            @else
                <!-- 空の状態 -->
                <div class="bg-white rounded-lg shadow-md p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">お気に入り店舗がありません</h3>
                    <p class="text-gray-600 mb-6">気になる店舗を見つけたら、ハートアイコンをタップしてお気に入りに追加しましょう</p>
                    <a href="{{ route('stores.index') }}"
                       class="inline-block px-6 py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-600 transition">
                        店舗を探す
                    </a>
                </div>
            @endif
        </div>
    </div>

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
                if (data.status === 'removed') {
                    location.reload();
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('エラーが発生しました');
            });
        }
    </script>
</x-layouts.public>
