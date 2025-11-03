<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            予約詳細
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
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

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- Status Badge -->
                    <div class="mb-6 flex items-center justify-between">
                        <div>
                            @if($reservation->status === 'pending')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                    承認待ち
                                </span>
                            @elseif($reservation->status === 'confirmed')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                    確定
                                </span>
                            @elseif($reservation->status === 'cancelled')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                    キャンセル
                                </span>
                            @elseif($reservation->status === 'completed')
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-blue-100 text-blue-800">
                                    完了
                                </span>
                            @else
                                <span class="px-3 py-1 inline-flex text-sm font-semibold rounded-full bg-gray-100 text-gray-800">
                                    {{ $reservation->status }}
                                </span>
                            @endif
                        </div>
                        <a href="{{ route('store.reservations.index') }}" class="text-sm text-orange-600 hover:text-orange-700 font-medium">
                            ← 一覧に戻る
                        </a>
                    </div>

                    <!-- Reservation Details -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <!-- Reservation Date & Time -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">予約日時</h3>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $reservation->reservation_date->format('Y年m月d日') }}
                            </p>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ substr($reservation->reservation_time, 0, 5) }}
                            </p>
                        </div>

                        <!-- Number of People -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">人数</h3>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $reservation->number_of_people }}名
                            </p>
                        </div>

                        <!-- Customer Name -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">お客様名</h3>
                            <p class="text-lg font-semibold text-gray-900">
                                {{ $reservation->customer_name }}
                            </p>
                        </div>

                        <!-- Phone Number -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">電話番号</h3>
                            <p class="text-lg font-semibold text-gray-900">
                                <a href="tel:{{ $reservation->customer_phone }}" class="text-orange-600 hover:text-orange-700">
                                    {{ $reservation->customer_phone }}
                                </a>
                            </p>
                        </div>

                        <!-- Email -->
                        @if($reservation->customer_email)
                            <div>
                                <h3 class="text-sm font-medium text-gray-500 mb-2">メールアドレス</h3>
                                <p class="text-lg font-semibold text-gray-900">
                                    <a href="mailto:{{ $reservation->customer_email }}" class="text-orange-600 hover:text-orange-700">
                                        {{ $reservation->customer_email }}
                                    </a>
                                </p>
                            </div>
                        @endif

                        <!-- Created At -->
                        <div>
                            <h3 class="text-sm font-medium text-gray-500 mb-2">予約作成日時</h3>
                            <p class="text-sm text-gray-900">
                                {{ $reservation->created_at->format('Y年m月d日 H:i') }}
                            </p>
                        </div>
                    </div>

                    <!-- Message -->
                    @if($reservation->message)
                        <div class="mb-6">
                            <h3 class="text-sm font-medium text-gray-500 mb-2">メッセージ</h3>
                            <div class="bg-gray-50 rounded-md p-4">
                                <p class="text-gray-900 whitespace-pre-wrap">{{ $reservation->message }}</p>
                            </div>
                        </div>
                    @endif

                    <!-- Status History -->
                    <div class="mb-6">
                        <h3 class="text-sm font-medium text-gray-500 mb-2">ステータス履歴</h3>
                        <div class="bg-gray-50 rounded-md p-4">
                            @if($reservation->confirmed_at)
                                <div class="mb-2">
                                    <span class="text-sm text-gray-600">確定日時: </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $reservation->confirmed_at->format('Y年m月d日 H:i') }}</span>
                                </div>
                            @endif
                            @if($reservation->cancelled_at)
                                <div class="mb-2">
                                    <span class="text-sm text-gray-600">キャンセル日時: </span>
                                    <span class="text-sm font-medium text-gray-900">{{ $reservation->cancelled_at->format('Y年m月d日 H:i') }}</span>
                                </div>
                            @endif
                            @if(!$reservation->confirmed_at && !$reservation->cancelled_at)
                                <p class="text-sm text-gray-500">まだステータスの変更はありません</p>
                            @endif
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex flex-wrap gap-3 pt-6 border-t border-gray-200">
                        @if($reservation->status === 'pending')
                            <form method="POST" action="{{ route('store.reservations.confirm', $reservation) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-md hover:bg-green-700" onclick="return confirm('予約を確定しますか？')">
                                    予約を確定する
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'confirmed')
                            <form method="POST" action="{{ route('store.reservations.complete', $reservation) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700" onclick="return confirm('予約を完了にしますか？')">
                                    完了にする
                                </button>
                            </form>
                        @endif

                        @if($reservation->status === 'pending' || $reservation->status === 'confirmed')
                            <form method="POST" action="{{ route('store.reservations.cancel', $reservation) }}" class="inline">
                                @csrf
                                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-md hover:bg-red-700" onclick="return confirm('本当にキャンセルしますか？')">
                                    予約をキャンセルする
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
