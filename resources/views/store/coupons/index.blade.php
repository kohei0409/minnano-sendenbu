<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            クーポン管理
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
                <a href="{{ route('store.coupons.create') }}" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                    新しいクーポンを作成
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">タイトル</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">コード</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">割引</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">期間</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">使用状況</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($coupons as $coupon)
                                <tr>
                                    <td class="px-6 py-4">
                                        <div class="text-sm font-medium text-gray-900">{{ $coupon->title }}</div>
                                        @if($coupon->description)
                                            <div class="text-sm text-gray-500">{{ Str::limit($coupon->description, 40) }}</div>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 text-xs font-mono bg-gray-100 text-gray-800 rounded">
                                            {{ $coupon->code }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($coupon->discount_type === 'percentage')
                                            {{ $coupon->discount_value }}%OFF
                                        @elseif($coupon->discount_type === 'amount')
                                            ¥{{ number_format($coupon->discount_value) }}OFF
                                        @else
                                            無料商品
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div>{{ $coupon->start_date->format('Y/m/d') }}</div>
                                        <div class="text-gray-500">~ {{ $coupon->end_date->format('Y/m/d') }}</div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        @if($coupon->usage_limit)
                                            {{ $coupon->used_count }} / {{ $coupon->usage_limit }}
                                        @else
                                            {{ $coupon->used_count }} / 無制限
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($coupon->is_active && $coupon->start_date <= now() && $coupon->end_date >= now())
                                            @if($coupon->usage_limit && $coupon->used_count >= $coupon->usage_limit)
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    上限到達
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    有効
                                                </span>
                                            @endif
                                        @elseif($coupon->start_date > now())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                開始前
                                            </span>
                                        @elseif($coupon->end_date < now())
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                期限切れ
                                            </span>
                                        @else
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                無効
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="{{ route('store.coupons.edit', $coupon) }}" class="text-orange-600 hover:text-orange-900 mr-3">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('store.coupons.destroy', $coupon) }}" class="inline" onsubmit="return confirm('本当に削除しますか？');">
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
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        クーポンがありません
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $coupons->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
