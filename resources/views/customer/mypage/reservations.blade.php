<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約履歴
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ステータスフィルター -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('customer.mypage.reservations') }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ !request('status') ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        全て ({{ Auth::user()->reservations()->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reservations', ['status' => 'confirmed']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'confirmed' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        予約確定 ({{ Auth::user()->reservations()->where('status', 'confirmed')->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reservations', ['status' => 'pending']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'pending' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        承認待ち ({{ Auth::user()->reservations()->where('status', 'pending')->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reservations', ['status' => 'cancelled']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'cancelled' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        キャンセル ({{ Auth::user()->reservations()->where('status', 'cancelled')->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reservations', ['status' => 'completed']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'completed' ? 'bg-orange-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        完了 ({{ Auth::user()->reservations()->where('status', 'completed')->count() }})
                    </a>
                </div>
            </div>

            <!-- 予約一覧 -->
            @if($reservations->count() > 0)
                <div class="space-y-4 mb-6">
                    @foreach($reservations as $reservation)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-start md:justify-between gap-4">
                                    <!-- 店舗情報 -->
                                    <div class="flex-1">
                                        <div class="flex items-start gap-4">
                                            <!-- 店舗画像 -->
                                            <div class="flex-shrink-0">
                                                @php
                                                    $mainImage = $reservation->store->images()->where('is_main', true)->first() ?? $reservation->store->images()->first();
                                                @endphp
                                                @if($mainImage)
                                                    <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                                                         alt="{{ $reservation->store->store_name }}"
                                                         class="w-24 h-24 object-cover rounded-lg">
                                                @else
                                                    <div class="w-24 h-24 bg-gray-200 rounded-lg flex items-center justify-center">
                                                        <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                                                        </svg>
                                                    </div>
                                                @endif
                                            </div>

                                            <!-- 予約詳細 -->
                                            <div class="flex-1">
                                                <div class="flex items-start justify-between mb-2">
                                                    <h3 class="text-lg font-bold text-gray-900">
                                                        {{ $reservation->store->store_name }}
                                                    </h3>
                                                    @if($reservation->status === 'pending')
                                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                            承認待ち
                                                        </span>
                                                    @elseif($reservation->status === 'confirmed')
                                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                            予約確定
                                                        </span>
                                                    @elseif($reservation->status === 'cancelled')
                                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                            キャンセル
                                                        </span>
                                                    @elseif($reservation->status === 'completed')
                                                        <span class="px-3 py-1 text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            完了
                                                        </span>
                                                    @endif
                                                </div>

                                                <div class="space-y-2">
                                                    <div class="flex items-center text-gray-700">
                                                        <svg class="w-5 h-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                                        </svg>
                                                        <span class="font-medium">{{ $reservation->reservation_date->format('Y年m月d日') }}</span>
                                                        <span class="ml-2">{{ substr($reservation->reservation_time, 0, 5) }}</span>
                                                    </div>

                                                    <div class="flex items-center text-gray-700">
                                                        <svg class="w-5 h-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                        </svg>
                                                        <span>{{ $reservation->number_of_people }}名</span>
                                                    </div>

                                                    @if($reservation->menu)
                                                        <div class="flex items-center text-gray-700">
                                                            <svg class="w-5 h-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/>
                                                            </svg>
                                                            <span>{{ $reservation->menu->name }}</span>
                                                        </div>
                                                    @endif

                                                    @if($reservation->special_request)
                                                        <div class="mt-2 p-3 bg-gray-50 rounded text-sm text-gray-600">
                                                            <p class="font-medium text-gray-700 mb-1">特記事項:</p>
                                                            <p>{{ $reservation->special_request }}</p>
                                                        </div>
                                                    @endif
                                                </div>

                                                <div class="mt-4 text-xs text-gray-500">
                                                    予約日: {{ $reservation->created_at->format('Y年m月d日 H:i') }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- アクションボタン -->
                                    <div class="flex flex-col gap-2 md:ml-4">
                                        <a href="{{ route('stores.show', $reservation->store) }}"
                                           class="px-4 py-2 bg-orange-500 text-white text-center font-medium rounded-lg hover:bg-orange-600 transition">
                                            店舗詳細
                                        </a>

                                        @if($reservation->status === 'confirmed' || $reservation->status === 'pending')
                                            <form action="{{ route('customer.reservations.cancel', $reservation) }}"
                                                  method="POST"
                                                  onsubmit="return confirm('この予約をキャンセルしてもよろしいですか?');">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                        class="w-full px-4 py-2 bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200 transition">
                                                    キャンセル
                                                </button>
                                            </form>
                                        @endif

                                        @if($reservation->status === 'completed' && !$reservation->review)
                                            <a href="{{ route('customer.reviews.create', ['store' => $reservation->store_id, 'reservation' => $reservation->id]) }}"
                                               class="px-4 py-2 bg-blue-500 text-white text-center font-medium rounded-lg hover:bg-blue-600 transition">
                                                レビューを書く
                                            </a>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ページネーション -->
                <div class="mt-6">
                    {{ $reservations->links() }}
                </div>
            @else
                <!-- 空の状態 -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">予約履歴がありません</h3>
                    <p class="text-gray-600 mb-6">気になる店舗を見つけて予約してみましょう</p>
                    <a href="{{ route('stores.index') }}"
                       class="inline-block px-6 py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-600 transition">
                        店舗を探す
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
