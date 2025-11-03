<x-layouts.public>
    <x-slot name="title">予約内容確認</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- ステータスメッセージ -->
            <div class="mb-6">
                @if($reservation->status === 'pending')
                    <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-yellow-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-yellow-900">予約受付中</h3>
                                <p class="text-sm text-yellow-800">店舗からの確認をお待ちください</p>
                            </div>
                        </div>
                    </div>
                @elseif($reservation->status === 'confirmed')
                    <div class="bg-green-50 border border-green-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-green-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-green-900">予約確定</h3>
                                <p class="text-sm text-green-800">予約が確定しました</p>
                            </div>
                        </div>
                    </div>
                @elseif($reservation->status === 'cancelled')
                    <div class="bg-red-50 border border-red-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-red-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-red-900">キャンセル済み</h3>
                                <p class="text-sm text-red-800">この予約はキャンセルされました</p>
                            </div>
                        </div>
                    </div>
                @elseif($reservation->status === 'completed')
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4">
                        <div class="flex items-center">
                            <svg class="w-6 h-6 text-blue-600 mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            <div>
                                <h3 class="font-medium text-blue-900">ご来店済み</h3>
                                <p class="text-sm text-blue-800">ありがとうございました</p>
                            </div>
                        </div>
                    </div>
                @endif
            </div>

            <!-- 予約詳細 -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">予約内容</h2>

                <!-- 店舗情報 -->
                <div class="border-b border-gray-200 pb-6 mb-6">
                    <h3 class="text-sm font-medium text-gray-700 mb-3">店舗情報</h3>
                    <div class="flex items-center space-x-4">
                        @php
                            $mainImage = $reservation->store->images()->where('is_main', true)->first() ?? $reservation->store->images()->first();
                        @endphp
                        @if($mainImage)
                            <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                                 alt="{{ $reservation->store->store_name }}"
                                 class="w-20 h-20 object-cover rounded-lg">
                        @else
                            <div class="w-20 h-20 bg-gray-200 rounded-lg"></div>
                        @endif
                        <div>
                            <h4 class="text-lg font-bold text-gray-900">{{ $reservation->store->store_name }}</h4>
                            <p class="text-sm text-gray-600">{{ $reservation->store->prefecture }}{{ $reservation->store->city }}{{ $reservation->store->street_address }}</p>
                            <p class="text-sm text-gray-600">TEL: {{ $reservation->store->phone }}</p>
                        </div>
                    </div>
                </div>

                <!-- 予約詳細 -->
                <div class="space-y-4">
                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">予約日時</span>
                        <span class="font-medium text-gray-900">
                            {{ $reservation->reservation_date->format('Y年m月d日') }}（{{ ['日', '月', '火', '水', '木', '金', '土'][$reservation->reservation_date->dayOfWeek] }}）
                            {{ substr($reservation->reservation_time, 0, 5) }}
                        </span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">人数</span>
                        <span class="font-medium text-gray-900">{{ $reservation->number_of_people }}名</span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">お名前</span>
                        <span class="font-medium text-gray-900">{{ $reservation->customer_name }}</span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">電話番号</span>
                        <span class="font-medium text-gray-900">{{ $reservation->customer_phone }}</span>
                    </div>

                    <div class="flex justify-between py-3 border-b border-gray-100">
                        <span class="text-gray-600">メールアドレス</span>
                        <span class="font-medium text-gray-900">{{ $reservation->customer_email }}</span>
                    </div>

                    @if($reservation->message)
                        <div class="py-3">
                            <span class="text-gray-600 block mb-2">備考・ご要望</span>
                            <p class="text-gray-900 bg-gray-50 p-4 rounded-md">{{ $reservation->message }}</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- アクション -->
            <div class="flex items-center justify-between">
                <a href="{{ route('stores.show', $reservation->store) }}"
                   class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                    店舗ページへ戻る
                </a>

                @if($reservation->status === 'confirmed' && auth('customer')->check() && auth('customer')->id() === $reservation->customer_id)
                    <a href="{{ route('customer.reviews.create', $reservation->store) }}"
                       class="px-6 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-md hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                        レビューを書く
                    </a>
                @endif
            </div>

            <!-- 注意事項 -->
            @if($reservation->status === 'confirmed')
                <div class="mt-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h3 class="font-medium text-orange-900 mb-2">ご来店時の注意事項</h3>
                    <ul class="text-sm text-orange-800 space-y-1">
                        <li>• 予約時間の10分前にはお越しください</li>
                        <li>• 遅れる場合は必ず店舗へご連絡ください</li>
                        <li>• キャンセルの場合は早めにご連絡をお願いします</li>
                    </ul>
                </div>
            @endif
        </div>
    </div>
</x-layouts.public>
