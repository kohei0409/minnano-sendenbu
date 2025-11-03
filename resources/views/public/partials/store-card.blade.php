<div class="bg-white rounded-lg shadow-md overflow-hidden hover:shadow-xl transition-shadow duration-300">
    <a href="{{ route('stores.show', $store) }}" class="block">
        <!-- Store Image -->
        <div class="h-48 bg-gray-200 relative">
            @php
                $mainImage = $store->images->where('image_type', 'main')->first() ?? $store->images->first();
            @endphp

            @if($mainImage)
                <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                     alt="{{ $store->store_name }}"
                     class="w-full h-full object-cover">
            @else
                <div class="w-full h-full flex items-center justify-center text-gray-400">
                    <svg class="w-16 h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
            @endif

            <!-- Featured Badge -->
            @if($store->is_featured && $store->featured_until && $store->featured_until->isFuture())
                <div class="absolute top-2 right-2 bg-orange-500 text-white text-xs font-bold px-3 py-1 rounded-full shadow-md">
                    店舗公式
                </div>
            @endif
        </div>

        <!-- Store Info -->
        <div class="p-4">
            <h3 class="text-lg font-bold text-gray-900 mb-2 line-clamp-1">
                {{ $store->store_name }}
            </h3>

            <!-- Category & Area -->
            <div class="flex flex-wrap gap-2 mb-3">
                @if($store->category)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-bold bg-orange-100 text-orange-700 rounded">
                        {{ $store->category->name }}
                    </span>
                @endif
                @if($store->area)
                    <span class="inline-flex items-center px-2 py-1 text-xs font-medium bg-blue-50 text-blue-700 rounded">
                        📍 {{ $store->area->name }}
                    </span>
                @endif
            </div>

            <!-- Rating -->
            <div class="flex items-center mb-2">
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
                    <span class="text-gray-400">({{ $store->review_count }}件)</span>
                </span>
            </div>

            <!-- Description -->
            @if($store->storeDetail && $store->storeDetail->description)
                <p class="text-sm text-gray-600 line-clamp-2">
                    {{ $store->storeDetail->description }}
                </p>
            @endif
        </div>
    </a>
</div>
