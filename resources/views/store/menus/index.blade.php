<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            メニュー管理
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('store.menus.create') }}" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                    新しいメニューを追加
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">画像</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">メニュー名</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">カテゴリ</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">価格</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">状態</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($menus as $menu)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($menu->image_path)
                                            <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="h-16 w-16 object-cover rounded">
                                        @else
                                            <div class="h-16 w-16 bg-gray-200 rounded flex items-center justify-center">
                                                <svg class="h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                </svg>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $menu->name }}</div>
                                        @if($menu->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($menu->description, 50) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($menu->category)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                {{ $menu->category }}
                                            </span>
                                        @else
                                            <span class="text-gray-400 text-sm">未設定</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        ¥{{ number_format($menu->price) }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($menu->is_available)
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                提供中
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                停止中
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('store.menus.edit', $menu) }}" class="text-orange-600 hover:text-orange-900 mr-3">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('store.menus.destroy', $menu) }}" class="inline" onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        メニューがありません
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $menus->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
