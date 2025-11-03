<x-layouts.public>
    <x-slot name="title">みんなの宣伝部 - 全国の店舗情報</x-slot>

    <!-- Hero Section with Search -->
    <div class="bg-gradient-to-r from-orange-500 to-orange-600 py-16">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-8">
                <h1 class="text-4xl font-bold text-white mb-4">全国の店舗情報を検索</h1>
                <p class="text-xl text-orange-100">レビュー・クーポン・予約まで、便利な機能が満載！</p>
            </div>

            <!-- Search Form -->
            <form action="{{ route('stores.index') }}" method="GET" class="max-w-4xl mx-auto">
                <div class="bg-white rounded-lg shadow-xl p-6">
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                        <!-- Keyword Search -->
                        <div class="md:col-span-3">
                            <input type="text"
                                   name="keyword"
                                   placeholder="地域、店舗名、キーワードで検索"
                                   class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none text-lg"
                                   value="{{ request('keyword') }}">
                        </div>

                        <!-- Category -->
                        <div>
                            <select name="category_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none">
                                <option value="">すべてのカテゴリー</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Area -->
                        <div>
                            <select name="area_id"
                                    class="w-full px-4 py-3 border-2 border-gray-300 rounded-lg focus:border-orange-500 focus:outline-none">
                                <option value="">すべてのエリア</option>
                                @foreach($areas as $area)
                                    <option value="{{ $area->id }}">{{ $area->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Search Button -->
                        <div>
                            <button type="submit"
                                    class="w-full px-6 py-3 bg-orange-500 text-white font-bold text-lg rounded-lg hover:bg-orange-600 transition shadow-md">
                                🔍 検索する
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <!-- Main Content -->
    <div class="bg-gray-50 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

            <!-- Category Icons -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-12">
                <h2 class="text-2xl font-bold text-gray-900 mb-6">カテゴリーから探す</h2>
                <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-6 gap-4">
                    @foreach($categories->take(12) as $category)
                        <a href="{{ route('stores.index', ['category_id' => $category->id]) }}"
                           class="flex flex-col items-center p-4 bg-gray-50 rounded-lg hover:bg-orange-50 hover:shadow-md transition">
                            <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-3">
                                <span class="text-3xl">🏪</span>
                            </div>
                            <span class="text-sm font-semibold text-gray-900 text-center">{{ $category->name }}</span>
                        </a>
                    @endforeach
                </div>
            </div>

            <!-- Featured Stores -->
            @if($featuredStores->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">⭐ 注目店舗</h2>
                        <a href="{{ route('stores.index') }}" class="text-orange-600 hover:text-orange-700 font-semibold">すべて見る →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($featuredStores as $store)
                            @include('public.partials.store-card', ['store' => $store])
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- New Stores -->
            @if($newStores->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">🆕 新着店舗</h2>
                        <a href="{{ route('stores.index', ['sort' => 'created_at']) }}" class="text-orange-600 hover:text-orange-700 font-semibold">すべて見る →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($newStores as $store)
                            @include('public.partials.store-card', ['store' => $store])
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- Popular Stores -->
            @if($popularStores->count() > 0)
                <div class="mb-12">
                    <div class="flex items-center justify-between mb-6">
                        <h2 class="text-2xl font-bold text-gray-900">🔥 人気店舗</h2>
                        <a href="{{ route('stores.index', ['sort' => 'rating']) }}" class="text-orange-600 hover:text-orange-700 font-semibold">すべて見る →</a>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($popularStores as $store)
                            @include('public.partials.store-card', ['store' => $store])
                        @endforeach
                    </div>
                </div>
            @endif

            <!-- CTA Section -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 rounded-lg shadow-xl p-8 text-center">
                <h2 class="text-3xl font-bold text-white mb-4">店舗オーナー様へ</h2>
                <p class="text-xl text-orange-100 mb-6">みんなの宣伝部に店舗を掲載しませんか？</p>
                <a href="{{ route('store-applications.create') }}"
                   class="inline-block px-8 py-4 bg-white text-orange-600 font-bold text-lg rounded-lg hover:bg-gray-100 transition shadow-md">
                    📝 店舗掲載を申請する
                </a>
            </div>

        </div>
    </div>
</x-layouts.public>
