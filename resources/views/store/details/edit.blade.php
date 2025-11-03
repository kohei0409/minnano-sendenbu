<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            店舗詳細情報編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Tab Navigation -->
            <div class="mb-6 border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button type="button" class="tab-button border-orange-500 text-orange-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="info">
                        店舗情報
                    </button>
                    <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="hours">
                        営業時間
                    </button>
                    <button type="button" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm" data-tab="images">
                        画像管理
                    </button>
                </nav>
            </div>

            <!-- Tab Content -->

            <!-- Tab 1: Store Information -->
            <div id="tab-info" class="tab-content">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('store.details.update') }}">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Category -->
                                <div>
                                    <label for="category_id" class="block text-sm font-medium text-gray-700">カテゴリ <span class="text-red-500">*</span></label>
                                    <select id="category_id" name="category_id" required
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('category_id') border-red-500 @enderror">
                                        <option value="">選択してください</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ old('category_id', $store->category_id) == $category->id ? 'selected' : '' }}>
                                                {{ $category->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('category_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Area -->
                                <div>
                                    <label for="area_id" class="block text-sm font-medium text-gray-700">エリア <span class="text-red-500">*</span></label>
                                    <select id="area_id" name="area_id" required
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('area_id') border-red-500 @enderror">
                                        <option value="">選択してください</option>
                                        @foreach($areas as $area)
                                            <option value="{{ $area->id }}" {{ old('area_id', $store->area_id) == $area->id ? 'selected' : '' }}>
                                                {{ $area->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('area_id')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Address Section -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">住所情報</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Postal Code -->
                                    <div>
                                        <label for="postal_code" class="block text-sm font-medium text-gray-700">郵便番号</label>
                                        <input id="postal_code" type="text" name="postal_code" value="{{ old('postal_code', $store->postal_code) }}"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('postal_code') border-red-500 @enderror"
                                            placeholder="例: 123-4567">
                                        @error('postal_code')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Prefecture -->
                                    <div>
                                        <label for="prefecture" class="block text-sm font-medium text-gray-700">都道府県</label>
                                        <input id="prefecture" type="text" name="prefecture" value="{{ old('prefecture', $store->prefecture) }}"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('prefecture') border-red-500 @enderror">
                                        @error('prefecture')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <label for="city" class="block text-sm font-medium text-gray-700">市区町村</label>
                                    <input id="city" type="text" name="city" value="{{ old('city', $store->city) }}"
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('city') border-red-500 @enderror">
                                    @error('city')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="street_address" class="block text-sm font-medium text-gray-700">番地</label>
                                    <input id="street_address" type="text" name="street_address" value="{{ old('street_address', $store->street_address) }}"
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('street_address') border-red-500 @enderror">
                                    @error('street_address')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4">
                                    <label for="building" class="block text-sm font-medium text-gray-700">建物名・部屋番号</label>
                                    <input id="building" type="text" name="building" value="{{ old('building', $store->building) }}"
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('building') border-red-500 @enderror">
                                    @error('building')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="mt-4 grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Latitude -->
                                    <div>
                                        <label for="latitude" class="block text-sm font-medium text-gray-700">緯度</label>
                                        <input id="latitude" type="number" name="latitude" value="{{ old('latitude', $store->latitude) }}" step="any"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('latitude') border-red-500 @enderror"
                                            placeholder="例: 35.6812">
                                        @error('latitude')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Longitude -->
                                    <div>
                                        <label for="longitude" class="block text-sm font-medium text-gray-700">経度</label>
                                        <input id="longitude" type="number" name="longitude" value="{{ old('longitude', $store->longitude) }}" step="any"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('longitude') border-red-500 @enderror"
                                            placeholder="例: 139.7671">
                                        @error('longitude')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Description -->
                            <div class="mt-6 border-t pt-6">
                                <label for="description" class="block text-sm font-medium text-gray-700">店舗説明</label>
                                <textarea id="description" name="description" rows="6"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $store->storeDetail->description ?? '') }}</textarea>
                                @error('description')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Access Info -->
                            <div class="mt-4">
                                <label for="access_info" class="block text-sm font-medium text-gray-700">アクセス情報</label>
                                <textarea id="access_info" name="access_info" rows="3"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('access_info') border-red-500 @enderror">{{ old('access_info', $store->storeDetail->access_info ?? '') }}</textarea>
                                @error('access_info')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Parking Info -->
                            <div class="mt-4">
                                <label for="parking_info" class="block text-sm font-medium text-gray-700">駐車場情報</label>
                                <textarea id="parking_info" name="parking_info" rows="3"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('parking_info') border-red-500 @enderror">{{ old('parking_info', $store->storeDetail->parking_info ?? '') }}</textarea>
                                @error('parking_info')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Payment Methods -->
                            <div class="mt-4">
                                <label for="payment_methods" class="block text-sm font-medium text-gray-700">支払い方法</label>
                                <textarea id="payment_methods" name="payment_methods" rows="3"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('payment_methods') border-red-500 @enderror"
                                    placeholder="例: 現金、クレジットカード、電子マネー">{{ old('payment_methods', $store->storeDetail->payment_methods ?? '') }}</textarea>
                                @error('payment_methods')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- Social Media URLs -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">SNS・Webサイト</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Website URL -->
                                    <div>
                                        <label for="website_url" class="block text-sm font-medium text-gray-700">Webサイト</label>
                                        <input id="website_url" type="url" name="website_url" value="{{ old('website_url', $store->storeDetail->website_url ?? '') }}"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('website_url') border-red-500 @enderror"
                                            placeholder="https://example.com">
                                        @error('website_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Facebook URL -->
                                    <div>
                                        <label for="facebook_url" class="block text-sm font-medium text-gray-700">Facebook</label>
                                        <input id="facebook_url" type="url" name="facebook_url" value="{{ old('facebook_url', $store->storeDetail->facebook_url ?? '') }}"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('facebook_url') border-red-500 @enderror"
                                            placeholder="https://facebook.com/...">
                                        @error('facebook_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Instagram URL -->
                                    <div>
                                        <label for="instagram_url" class="block text-sm font-medium text-gray-700">Instagram</label>
                                        <input id="instagram_url" type="url" name="instagram_url" value="{{ old('instagram_url', $store->storeDetail->instagram_url ?? '') }}"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('instagram_url') border-red-500 @enderror"
                                            placeholder="https://instagram.com/...">
                                        @error('instagram_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Twitter URL -->
                                    <div>
                                        <label for="twitter_url" class="block text-sm font-medium text-gray-700">Twitter (X)</label>
                                        <input id="twitter_url" type="url" name="twitter_url" value="{{ old('twitter_url', $store->storeDetail->twitter_url ?? '') }}"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('twitter_url') border-red-500 @enderror"
                                            placeholder="https://twitter.com/...">
                                        @error('twitter_url')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <!-- Facility Information -->
                            <div class="mt-6 border-t pt-6">
                                <h3 class="text-lg font-medium text-gray-900 mb-4">施設情報</h3>

                                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                    <!-- Seats -->
                                    <div>
                                        <label for="seats" class="block text-sm font-medium text-gray-700">座席数</label>
                                        <input id="seats" type="number" name="seats" value="{{ old('seats', $store->storeDetail->seats ?? '') }}" min="0"
                                            class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('seats') border-red-500 @enderror">
                                        @error('seats')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Private Rooms -->
                                    <div class="flex items-center h-full pt-7">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="private_rooms" value="1" {{ old('private_rooms', $store->storeDetail->private_rooms ?? false) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">個室あり</span>
                                        </label>
                                        @error('private_rooms')
                                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <!-- Smoking -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">喫煙</label>
                                    <div class="flex gap-6">
                                        <label class="flex items-center">
                                            <input type="radio" name="smoking" value="allowed" {{ old('smoking', $store->storeDetail->smoking ?? '') == 'allowed' ? 'checked' : '' }}
                                                class="border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">可</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="smoking" value="separated" {{ old('smoking', $store->storeDetail->smoking ?? '') == 'separated' ? 'checked' : '' }}
                                                class="border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">分煙</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="smoking" value="prohibited" {{ old('smoking', $store->storeDetail->smoking ?? '') == 'prohibited' ? 'checked' : '' }}
                                                class="border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">禁煙</span>
                                        </label>
                                    </div>
                                    @error('smoking')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Amenities -->
                                <div class="mt-4">
                                    <label class="block text-sm font-medium text-gray-700 mb-2">設備</label>
                                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                        <label class="flex items-center">
                                            <input type="checkbox" name="wifi" value="1" {{ old('wifi', $store->storeDetail->wifi ?? false) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">Wi-Fi</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="power_outlet" value="1" {{ old('power_outlet', $store->storeDetail->power_outlet ?? false) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">電源コンセント</span>
                                        </label>
                                        <label class="flex items-center">
                                            <input type="checkbox" name="credit_card" value="1" {{ old('credit_card', $store->storeDetail->credit_card ?? false) ? 'checked' : '' }}
                                                class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                            <span class="ml-2 text-sm text-gray-700">クレジットカード</span>
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="flex items-center justify-end mt-6 gap-4">
                                <a href="{{ route('store.dashboard') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                    キャンセル
                                </a>
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                    更新
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 2: Business Hours -->
            <div id="tab-hours" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <form method="POST" action="{{ route('store.details.business-hours.update') }}">
                            @csrf
                            @method('PATCH')

                            <div class="space-y-6">
                                @php
                                    $dayNames = ['日曜日', '月曜日', '火曜日', '水曜日', '木曜日', '金曜日', '土曜日'];
                                    $businessHours = $store->businessHours->keyBy('day_of_week');
                                @endphp

                                @for($day = 0; $day <= 6; $day++)
                                    @php
                                        $hours = $businessHours->get($day);
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <div class="flex items-center justify-between mb-4">
                                            <h4 class="text-lg font-medium text-gray-900">{{ $dayNames[$day] }}</h4>
                                            <label class="flex items-center">
                                                <input type="checkbox" name="hours[{{ $day }}][is_closed]" value="1"
                                                    {{ old("hours.$day.is_closed", $hours->is_closed ?? false) ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50"
                                                    onchange="toggleDayFields({{ $day }})">
                                                <span class="ml-2 text-sm text-gray-700">定休日</span>
                                            </label>
                                        </div>

                                        <input type="hidden" name="hours[{{ $day }}][day_of_week]" value="{{ $day }}">

                                        <div id="day-fields-{{ $day }}" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                            <div>
                                                <label for="open_time_{{ $day }}" class="block text-sm font-medium text-gray-700">開店時間</label>
                                                <input id="open_time_{{ $day }}" type="time" name="hours[{{ $day }}][open_time]"
                                                    value="{{ old("hours.$day.open_time", $hours->open_time ?? '') }}"
                                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                                            </div>

                                            <div>
                                                <label for="close_time_{{ $day }}" class="block text-sm font-medium text-gray-700">閉店時間</label>
                                                <input id="close_time_{{ $day }}" type="time" name="hours[{{ $day }}][close_time]"
                                                    value="{{ old("hours.$day.close_time", $hours->close_time ?? '') }}"
                                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                                            </div>

                                            <div>
                                                <label for="break_start_{{ $day }}" class="block text-sm font-medium text-gray-700">休憩開始</label>
                                                <input id="break_start_{{ $day }}" type="time" name="hours[{{ $day }}][break_start]"
                                                    value="{{ old("hours.$day.break_start", $hours->break_start ?? '') }}"
                                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                                            </div>

                                            <div>
                                                <label for="break_end_{{ $day }}" class="block text-sm font-medium text-gray-700">休憩終了</label>
                                                <input id="break_end_{{ $day }}" type="time" name="hours[{{ $day }}][break_end]"
                                                    value="{{ old("hours.$day.break_end", $hours->break_end ?? '') }}"
                                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500">
                                            </div>
                                        </div>
                                    </div>
                                @endfor
                            </div>

                            @if($errors->has('hours'))
                                <p class="mt-4 text-sm text-red-600">{{ $errors->first('hours') }}</p>
                            @endif

                            <div class="flex items-center justify-end mt-6 gap-4">
                                <a href="{{ route('store.dashboard') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                    キャンセル
                                </a>
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                    営業時間を更新
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Tab 3: Images -->
            <div id="tab-images" class="tab-content hidden">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">画像をアップロード</h3>
                        <form method="POST" action="{{ route('store.details.images.upload') }}" enctype="multipart/form-data">
                            @csrf

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <!-- Image File -->
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700">画像 <span class="text-red-500">*</span></label>
                                    <input id="image" type="file" name="image" accept="image/*" required
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('image') border-red-500 @enderror">
                                    <p class="mt-1 text-xs text-gray-500">最大5MBまで。JPG、PNG、GIF形式をサポート</p>
                                    @error('image')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Image Type -->
                                <div>
                                    <label for="image_type" class="block text-sm font-medium text-gray-700">画像タイプ <span class="text-red-500">*</span></label>
                                    <select id="image_type" name="image_type" required
                                        class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('image_type') border-red-500 @enderror">
                                        <option value="">選択してください</option>
                                        <option value="main" {{ old('image_type') == 'main' ? 'selected' : '' }}>メイン画像</option>
                                        <option value="exterior" {{ old('image_type') == 'exterior' ? 'selected' : '' }}>外観</option>
                                        <option value="interior" {{ old('image_type') == 'interior' ? 'selected' : '' }}>内観</option>
                                        <option value="menu" {{ old('image_type') == 'menu' ? 'selected' : '' }}>メニュー</option>
                                        <option value="other" {{ old('image_type') == 'other' ? 'selected' : '' }}>その他</option>
                                    </select>
                                    @error('image_type')
                                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>

                            <!-- Caption -->
                            <div class="mt-4">
                                <label for="caption" class="block text-sm font-medium text-gray-700">キャプション</label>
                                <input id="caption" type="text" name="caption" value="{{ old('caption') }}"
                                    class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('caption') border-red-500 @enderror"
                                    placeholder="画像の説明を入力">
                                @error('caption')
                                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                                @enderror
                            </div>

                            <div class="flex items-center justify-end mt-6">
                                <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                    アップロード
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- Existing Images -->
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">登録済み画像</h3>

                        @if($store->images->isEmpty())
                            <p class="text-gray-500 text-center py-8">画像が登録されていません</p>
                        @else
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                                @foreach($store->images as $image)
                                    <div class="border border-gray-200 rounded-lg p-4">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="{{ $image->caption }}"
                                            class="w-full h-48 object-cover rounded-md mb-3">

                                        <div class="space-y-2">
                                            <div>
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                    {{ $image->image_type == 'main' ? 'bg-orange-100 text-orange-800' : 'bg-gray-100 text-gray-800' }}">
                                                    @switch($image->image_type)
                                                        @case('main') メイン @break
                                                        @case('exterior') 外観 @break
                                                        @case('interior') 内観 @break
                                                        @case('menu') メニュー @break
                                                        @case('other') その他 @break
                                                    @endswitch
                                                </span>
                                            </div>

                                            @if($image->caption)
                                                <p class="text-sm text-gray-600">{{ $image->caption }}</p>
                                            @endif

                                            <form method="POST" action="{{ route('store.details.images.delete', $image) }}"
                                                onsubmit="return confirm('この画像を削除してもよろしいですか?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-full px-3 py-2 bg-red-600 text-white text-sm rounded-md hover:bg-red-700">
                                                    削除
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Tab switching functionality
        document.addEventListener('DOMContentLoaded', function() {
            const tabButtons = document.querySelectorAll('.tab-button');
            const tabContents = document.querySelectorAll('.tab-content');

            tabButtons.forEach(button => {
                button.addEventListener('click', function() {
                    const tabName = this.dataset.tab;

                    // Update button styles
                    tabButtons.forEach(btn => {
                        btn.classList.remove('border-orange-500', 'text-orange-600');
                        btn.classList.add('border-transparent', 'text-gray-500');
                    });
                    this.classList.remove('border-transparent', 'text-gray-500');
                    this.classList.add('border-orange-500', 'text-orange-600');

                    // Show/hide content
                    tabContents.forEach(content => {
                        content.classList.add('hidden');
                    });
                    document.getElementById('tab-' + tabName).classList.remove('hidden');
                });
            });

            // Initialize business hours form state
            for (let day = 0; day <= 6; day++) {
                toggleDayFields(day);
            }
        });

        // Toggle day fields based on is_closed checkbox
        function toggleDayFields(day) {
            const checkbox = document.querySelector(`input[name="hours[${day}][is_closed]"]`);
            const fields = document.getElementById(`day-fields-${day}`);
            const inputs = fields.querySelectorAll('input[type="time"]');

            if (checkbox.checked) {
                fields.style.opacity = '0.5';
                inputs.forEach(input => input.disabled = true);
            } else {
                fields.style.opacity = '1';
                inputs.forEach(input => input.disabled = false);
            }
        }
    </script>
</x-app-layout>
