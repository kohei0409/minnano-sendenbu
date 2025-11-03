<x-layouts.public>
    <x-slot name="title">予約する - {{ $store->store_name }}</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- パンくずリスト -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-orange-600">ホーム</a></li>
                    <li>/</li>
                    <li><a href="{{ route('stores.index') }}" class="hover:text-orange-600">店舗一覧</a></li>
                    <li>/</li>
                    <li><a href="{{ route('stores.show', $store) }}" class="hover:text-orange-600">{{ $store->store_name }}</a></li>
                    <li>/</li>
                    <li class="text-gray-900">予約</li>
                </ol>
            </nav>

            <!-- 店舗情報 -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center space-x-4">
                    @php
                        $mainImage = $store->images()->where('is_main', true)->first() ?? $store->images()->first();
                    @endphp
                    @if($mainImage)
                        <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                             alt="{{ $store->store_name }}"
                             class="w-20 h-20 object-cover rounded-lg">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg"></div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $store->store_name }}</h1>
                        <p class="text-sm text-gray-600">{{ $store->category->name ?? '' }} / {{ $store->prefecture }}{{ $store->city }}</p>
                    </div>
                </div>

                <!-- 営業時間 -->
                @if($store->businessHours->count() > 0)
                    <div class="mt-4 pt-4 border-t border-gray-200">
                        <h3 class="text-sm font-medium text-gray-700 mb-2">営業時間</h3>
                        <div class="text-sm text-gray-600 space-y-1">
                            @php
                                $days = ['日', '月', '火', '水', '木', '金', '土'];
                            @endphp
                            @foreach($store->businessHours as $hours)
                                <div class="flex justify-between">
                                    <span>{{ $days[$hours->day_of_week] }}曜日:</span>
                                    <span>
                                        @if($hours->is_closed)
                                            <span class="text-red-600">定休日</span>
                                        @else
                                            {{ substr($hours->open_time, 0, 5) }} - {{ substr($hours->close_time, 0, 5) }}
                                        @endif
                                    </span>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- 予約フォーム -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">予約情報を入力</h2>

                <form action="{{ route('reservations.store', $store) }}" method="POST">
                    @csrf

                    <!-- 予約日 -->
                    <div class="mb-6">
                        <label for="reservation_date" class="block text-sm font-medium text-gray-700 mb-2">
                            予約日 <span class="text-red-600">*</span>
                        </label>
                        <input type="date"
                               name="reservation_date"
                               id="reservation_date"
                               value="{{ old('reservation_date') }}"
                               min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('reservation_date') border-red-500 @enderror"
                               required>
                        @error('reservation_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 予約時間 -->
                    <div class="mb-6">
                        <label for="reservation_time" class="block text-sm font-medium text-gray-700 mb-2">
                            予約時間 <span class="text-red-600">*</span>
                        </label>
                        <select name="reservation_time"
                                id="reservation_time"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('reservation_time') border-red-500 @enderror"
                                required>
                            <option value="">時間を選択してください</option>
                            @for($hour = 10; $hour <= 21; $hour++)
                                @foreach(['00', '30'] as $minute)
                                    @php
                                        $time = sprintf('%02d:%s', $hour, $minute);
                                    @endphp
                                    <option value="{{ $time }}" {{ old('reservation_time') == $time ? 'selected' : '' }}>
                                        {{ $time }}
                                    </option>
                                @endforeach
                            @endfor
                        </select>
                        @error('reservation_time')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 人数 -->
                    <div class="mb-6">
                        <label for="number_of_people" class="block text-sm font-medium text-gray-700 mb-2">
                            人数 <span class="text-red-600">*</span>
                        </label>
                        <select name="number_of_people"
                                id="number_of_people"
                                class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('number_of_people') border-red-500 @enderror"
                                required>
                            <option value="">人数を選択してください</option>
                            @for($i = 1; $i <= 10; $i++)
                                <option value="{{ $i }}" {{ old('number_of_people') == $i ? 'selected' : '' }}>
                                    {{ $i }}名
                                </option>
                            @endfor
                            @for($i = 15; $i <= 50; $i += 5)
                                <option value="{{ $i }}" {{ old('number_of_people') == $i ? 'selected' : '' }}>
                                    {{ $i }}名
                                </option>
                            @endfor
                        </select>
                        @error('number_of_people')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- お客様情報 -->
                    <div class="border-t border-gray-200 pt-6 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">お客様情報</h3>

                        <!-- 氏名 -->
                        <div class="mb-4">
                            <label for="customer_name" class="block text-sm font-medium text-gray-700 mb-2">
                                お名前 <span class="text-red-600">*</span>
                            </label>
                            <input type="text"
                                   name="customer_name"
                                   id="customer_name"
                                   value="{{ old('customer_name', auth('customer')->user()->name ?? '') }}"
                                   placeholder="山田 太郎"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('customer_name') border-red-500 @enderror"
                                   required>
                            @error('customer_name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- 電話番号 -->
                        <div class="mb-4">
                            <label for="customer_phone" class="block text-sm font-medium text-gray-700 mb-2">
                                電話番号 <span class="text-red-600">*</span>
                            </label>
                            <input type="tel"
                                   name="customer_phone"
                                   id="customer_phone"
                                   value="{{ old('customer_phone', auth('customer')->user()->phone ?? '') }}"
                                   placeholder="090-1234-5678"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('customer_phone') border-red-500 @enderror"
                                   required>
                            @error('customer_phone')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- メールアドレス -->
                        <div class="mb-4">
                            <label for="customer_email" class="block text-sm font-medium text-gray-700 mb-2">
                                メールアドレス <span class="text-red-600">*</span>
                            </label>
                            <input type="email"
                                   name="customer_email"
                                   id="customer_email"
                                   value="{{ old('customer_email', auth('customer')->user()->email ?? '') }}"
                                   placeholder="example@example.com"
                                   class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('customer_email') border-red-500 @enderror"
                                   required>
                            @error('customer_email')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <!-- 備考・要望 -->
                    <div class="mb-6">
                        <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                            備考・ご要望
                        </label>
                        <textarea name="message"
                                  id="message"
                                  rows="4"
                                  placeholder="アレルギーや席の希望などがあればご記入ください"
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('message') border-red-500 @enderror">{{ old('message') }}</textarea>
                        @error('message')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 注意事項 -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-blue-900 mb-2">予約に関する注意事項</h3>
                        <ul class="text-sm text-blue-800 space-y-1">
                            <li>• 予約リクエスト送信後、店舗からの確認をお待ちください</li>
                            <li>• 予約確定後、登録のメールアドレスに確認メールが送信されます</li>
                            <li>• キャンセルの場合は早めにご連絡ください</li>
                            <li>• 予約時間に遅れる場合は店舗へ直接お電話ください</li>
                        </ul>
                    </div>

                    <!-- ボタン -->
                    <div class="flex items-center justify-between space-x-4">
                        <a href="{{ route('stores.show', $store) }}"
                           class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                            キャンセル
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-md hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                            予約リクエストを送信
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.public>
