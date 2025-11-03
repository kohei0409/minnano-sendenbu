<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約管理
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

            <!-- Filter Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6">
                    <form method="GET" action="{{ route('store.reservations.index') }}" class="flex flex-wrap gap-4">
                        <!-- Status Filter -->
                        <div class="flex-1 min-w-[200px]">
                            <label for="status" class="block text-sm font-medium text-gray-700 mb-1">ステータス</label>
                            <select id="status" name="status"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                                <option value="">すべて</option>
                                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>承認待ち</option>
                                <option value="confirmed" {{ request('status') === 'confirmed' ? 'selected' : '' }}>確定</option>
                                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>キャンセル</option>
                                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>完了</option>
                            </select>
                        </div>

                        <!-- Date Filter -->
                        <div class="flex-1 min-w-[200px]">
                            <label for="date" class="block text-sm font-medium text-gray-700 mb-1">予約日</label>
                            <input id="date" type="date" name="date" value="{{ request('date') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                        </div>

                        <!-- Submit Button -->
                        <div class="flex items-end">
                            <button type="submit" class="px-6 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                検索
                            </button>
                            @if(request('status') || request('date'))
                                <a href="{{ route('store.reservations.index') }}" class="ml-2 px-6 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                    クリア
                                </a>
                            @endif
                        </div>
                    </form>
                </div>
            </div>

            <!-- Reservations Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">予約日時</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">お客様名</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">人数</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">電話番号</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">アクション</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($reservations as $reservation)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">
                                                {{ $reservation->reservation_date->format('Y年m月d日') }}
                                            </div>
                                            <div class="text-sm text-gray-500">
                                                {{ substr($reservation->reservation_time, 0, 5) }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="text-sm font-medium text-gray-900">{{ $reservation->customer_name }}</div>
                                            @if($reservation->customer_email)
                                                <div class="text-sm text-gray-500">{{ $reservation->customer_email }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $reservation->number_of_people }}名
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            {{ $reservation->customer_phone }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($reservation->status === 'pending')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                    承認待ち
                                                </span>
                                            @elseif($reservation->status === 'confirmed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    確定
                                                </span>
                                            @elseif($reservation->status === 'cancelled')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                                    キャンセル
                                                </span>
                                            @elseif($reservation->status === 'completed')
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                    完了
                                                </span>
                                            @else
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">
                                                    {{ $reservation->status }}
                                                </span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <div class="flex gap-2">
                                                <a href="{{ route('store.reservations.show', $reservation) }}" class="text-blue-600 hover:text-blue-900">
                                                    詳細
                                                </a>

                                                @if($reservation->status === 'pending')
                                                    <form method="POST" action="{{ route('store.reservations.confirm', $reservation) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-green-600 hover:text-green-900" onclick="return confirm('予約を確定しますか？')">
                                                            確定
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($reservation->status === 'confirmed')
                                                    <form method="POST" action="{{ route('store.reservations.complete', $reservation) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-blue-600 hover:text-blue-900" onclick="return confirm('予約を完了にしますか？')">
                                                            完了
                                                        </button>
                                                    </form>
                                                @endif

                                                @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                                                    <form method="POST" action="{{ route('store.reservations.cancel', $reservation) }}" class="inline">
                                                        @csrf
                                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('予約をキャンセルしますか？')">
                                                            キャンセル
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                            予約がありません
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $reservations->withQueryString()->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
