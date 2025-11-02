<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('店舗ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">ようこそ、{{ Auth::user()->name }}さん</h3>
                    
                    @if(Auth::user()->store)
                        <div class="mb-6 p-4 bg-blue-50 rounded-lg border border-blue-200">
                            <h4 class="font-bold text-lg mb-2">店舗情報</h4>
                            <p><strong>店舗名:</strong> {{ Auth::user()->store->store_name }}</p>
                            <p><strong>業種:</strong> {{ Auth::user()->store->industry }}</p>
                            <p><strong>ステータス:</strong> {{ Auth::user()->store->status }}</p>
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if(Auth::user()->isStoreOwner())
                            <a href="{{ route('store.staff.index') }}" class="block p-6 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200">
                                <h4 class="font-bold text-lg mb-2">スタッフ管理</h4>
                                <p class="text-gray-700">スタッフの追加・編集・権限管理</p>
                            </a>
                        @endif

                        <div class="block p-6 bg-gray-50 rounded-lg border border-gray-200">
                            <h4 class="font-bold text-lg mb-2">投稿管理</h4>
                            <p class="text-gray-700">投稿の作成・編集（準備中）</p>
                        </div>
                    </div>

                    <div class="mt-6">
                        <p class="text-sm text-gray-600">
                            ユーザーレベル: <span class="font-semibold">{{ Auth::user()->user_level }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
