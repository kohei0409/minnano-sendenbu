<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="robots" content="noindex, nofollow">

        <title>{{ $title ?? 'みんなの宣伝部' }} - {{ config('app.name', 'Laravel') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Scripts -->
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased bg-gray-50">
        <div class="min-h-screen">
            <!-- Top Bar -->
            <div class="bg-gradient-to-r from-orange-500 to-orange-600 text-white py-2">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center text-sm">
                        <div class="flex items-center space-x-4">
                            <span>📍 全国の店舗情報を掲載中</span>
                        </div>
                        <div class="flex items-center space-x-4">
                            @auth('customer')
                                <a href="{{ route('customer.favorites.index') }}" class="hover:text-orange-100 flex items-center">
                                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M3.172 5.172a4 4 0 015.656 0L10 6.343l1.172-1.171a4 4 0 115.656 5.656L10 17.657l-6.828-6.829a4 4 0 010-5.656z"/>
                                    </svg>
                                    お気に入り
                                </a>
                                <span>{{ auth('customer')->user()->name }}さん</span>
                                <form method="POST" action="{{ route('customer.logout') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="hover:text-orange-100">ログアウト</button>
                                </form>
                            @else
                                <a href="{{ route('store-applications.create') }}" class="hover:text-orange-100">店舗掲載</a>
                                <a href="{{ route('customer.login') }}" class="hover:text-orange-100">ログイン</a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>

            <!-- Main Navigation -->
            <nav class="bg-white shadow-md sticky top-0 z-50">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between items-center h-20">
                        <!-- Logo -->
                        <div class="flex items-center">
                            <a href="{{ route('stores.index') }}" class="flex items-center">
                                <div class="text-3xl font-bold">
                                    <span class="text-orange-500">みんなの</span>
                                    <span class="text-gray-800">宣伝部</span>
                                </div>
                            </a>
                        </div>

                        <!-- Search Bar (Desktop) -->
                        <div class="hidden md:flex flex-1 max-w-2xl mx-8">
                            <form action="{{ route('stores.index') }}" method="GET" class="w-full">
                                <div class="relative">
                                    <input type="text"
                                           name="keyword"
                                           placeholder="地域、店舗名、キーワードで検索"
                                           class="w-full px-4 py-3 pl-12 pr-20 border-2 border-orange-300 rounded-full focus:border-orange-500 focus:outline-none text-gray-700"
                                           value="{{ request('keyword') }}">
                                    <svg class="absolute left-4 top-3.5 h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                    </svg>
                                    <button type="submit" class="absolute right-2 top-2 bg-orange-500 text-white px-6 py-2 rounded-full hover:bg-orange-600 font-semibold">
                                        検索
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Right Menu -->
                        <div class="flex items-center space-x-4">
                            <a href="{{ route('stores.index') }}" class="hidden lg:flex items-center text-gray-700 hover:text-orange-500 font-medium">
                                <svg class="w-5 h-5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                                </svg>
                                店舗を探す
                            </a>
                        </div>
                    </div>

                    <!-- Mobile Search -->
                    <div class="md:hidden pb-4">
                        <form action="{{ route('stores.index') }}" method="GET">
                            <div class="relative">
                                <input type="text"
                                       name="keyword"
                                       placeholder="店舗名、キーワードで検索"
                                       class="w-full px-4 py-2 pl-10 border-2 border-orange-300 rounded-full focus:border-orange-500 focus:outline-none"
                                       value="{{ request('keyword') }}">
                                <svg class="absolute left-3 top-2.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                        </form>
                    </div>
                </div>
            </nav>

            <!-- Flash Messages -->
            @if (session('success'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 mt-4">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline">{{ session('error') }}</span>
                    </div>
                </div>
            @endif

            <!-- Page Content -->
            <main>
                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-gradient-to-b from-gray-800 to-gray-900 text-white mt-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-8 mb-8">
                        <div>
                            <h3 class="text-2xl font-bold mb-4">
                                <span class="text-orange-400">みんなの</span><span>宣伝部</span>
                            </h3>
                            <p class="text-gray-400 text-sm leading-relaxed">
                                日本全国の店舗情報を掲載。<br>
                                レビュー・クーポン・予約まで<br>
                                便利な機能が満載！
                            </p>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4 text-orange-400">店舗オーナー様</h4>
                            <ul class="space-y-2 text-sm text-gray-300">
                                <li><a href="{{ route('store-applications.create') }}" class="hover:text-orange-400 transition">📝 店舗掲載申請</a></li>
                                <li><a href="{{ route('login') }}" class="hover:text-orange-400 transition">🏪 店舗管理画面</a></li>
                                <li><a href="#" class="hover:text-orange-400 transition">💰 料金プラン</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4 text-orange-400">ご利用ガイド</h4>
                            <ul class="space-y-2 text-sm text-gray-300">
                                <li><a href="#" class="hover:text-orange-400 transition">使い方ガイド</a></li>
                                <li><a href="#" class="hover:text-orange-400 transition">よくある質問</a></li>
                                <li><a href="#" class="hover:text-orange-400 transition">利用規約</a></li>
                                <li><a href="#" class="hover:text-orange-400 transition">プライバシーポリシー</a></li>
                            </ul>
                        </div>
                        <div>
                            <h4 class="font-bold mb-4 text-orange-400">お問い合わせ</h4>
                            <ul class="space-y-2 text-sm text-gray-300">
                                <li><a href="#" class="hover:text-orange-400 transition">📧 お問い合わせ</a></li>
                                <li><a href="#" class="hover:text-orange-400 transition">🏢 運営会社</a></li>
                                <li class="pt-4">
                                    <div class="flex space-x-3">
                                        <a href="#" class="text-gray-400 hover:text-orange-400 transition">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/></svg>
                                        </a>
                                        <a href="#" class="text-gray-400 hover:text-orange-400 transition">
                                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.626 0-12 5.373-12 12 0 5.302 3.438 9.8 8.207 11.387.599.111.793-.261.793-.577v-2.234c-3.338.726-4.033-1.416-4.033-1.416-.546-1.387-1.333-1.756-1.333-1.756-1.089-.745.083-.729.083-.729 1.205.084 1.839 1.237 1.839 1.237 1.07 1.834 2.807 1.304 3.492.997.107-.775.418-1.305.762-1.604-2.665-.305-5.467-1.334-5.467-5.931 0-1.311.469-2.381 1.236-3.221-.124-.303-.535-1.524.117-3.176 0 0 1.008-.322 3.301 1.23.957-.266 1.983-.399 3.003-.404 1.02.005 2.047.138 3.006.404 2.291-1.552 3.297-1.23 3.297-1.23.653 1.653.242 2.874.118 3.176.77.84 1.235 1.911 1.235 3.221 0 4.609-2.807 5.624-5.479 5.921.43.372.823 1.102.823 2.222v3.293c0 .319.192.694.801.576 4.765-1.589 8.199-6.086 8.199-11.386 0-6.627-5.373-12-12-12z"/></svg>
                                        </a>
                                    </div>
                                </li>
                            </ul>
                        </div>
                    </div>
                    <div class="border-t border-gray-700 pt-8 text-center">
                        <p class="text-sm text-gray-400">
                            &copy; {{ date('Y') }} みんなの宣伝部 All rights reserved.
                        </p>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
