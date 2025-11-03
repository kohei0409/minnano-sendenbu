<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                エリア新規作成
            </h2>
            <a href="{{ route('admin.areas.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <form action="{{ route('admin.areas.store') }}" method="POST">
                        @csrf

                        <div class="mb-6">
                            <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                エリア名 <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="name" id="name" value="{{ old('name') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('name') border-red-500 @enderror"
                                required>
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="slug" class="block text-sm font-medium text-gray-700 mb-2">
                                スラッグ <span class="text-red-500">*</span>
                            </label>
                            <input type="text" name="slug" id="slug" value="{{ old('slug') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('slug') border-red-500 @enderror"
                                required>
                            <p class="mt-1 text-sm text-gray-500">URL用の識別子 (例: shibuya)</p>
                            @error('slug')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="prefecture" class="block text-sm font-medium text-gray-700 mb-2">
                                都道府県 <span class="text-red-500">*</span>
                            </label>
                            <select name="prefecture" id="prefecture"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('prefecture') border-red-500 @enderror"
                                required>
                                <option value="">-- 都道府県を選択 --</option>
                                <option value="北海道" {{ old('prefecture') == '北海道' ? 'selected' : '' }}>北海道</option>
                                <option value="青森県" {{ old('prefecture') == '青森県' ? 'selected' : '' }}>青森県</option>
                                <option value="岩手県" {{ old('prefecture') == '岩手県' ? 'selected' : '' }}>岩手県</option>
                                <option value="宮城県" {{ old('prefecture') == '宮城県' ? 'selected' : '' }}>宮城県</option>
                                <option value="秋田県" {{ old('prefecture') == '秋田県' ? 'selected' : '' }}>秋田県</option>
                                <option value="山形県" {{ old('prefecture') == '山形県' ? 'selected' : '' }}>山形県</option>
                                <option value="福島県" {{ old('prefecture') == '福島県' ? 'selected' : '' }}>福島県</option>
                                <option value="茨城県" {{ old('prefecture') == '茨城県' ? 'selected' : '' }}>茨城県</option>
                                <option value="栃木県" {{ old('prefecture') == '栃木県' ? 'selected' : '' }}>栃木県</option>
                                <option value="群馬県" {{ old('prefecture') == '群馬県' ? 'selected' : '' }}>群馬県</option>
                                <option value="埼玉県" {{ old('prefecture') == '埼玉県' ? 'selected' : '' }}>埼玉県</option>
                                <option value="千葉県" {{ old('prefecture') == '千葉県' ? 'selected' : '' }}>千葉県</option>
                                <option value="東京都" {{ old('prefecture') == '東京都' ? 'selected' : '' }}>東京都</option>
                                <option value="神奈川県" {{ old('prefecture') == '神奈川県' ? 'selected' : '' }}>神奈川県</option>
                                <option value="新潟県" {{ old('prefecture') == '新潟県' ? 'selected' : '' }}>新潟県</option>
                                <option value="富山県" {{ old('prefecture') == '富山県' ? 'selected' : '' }}>富山県</option>
                                <option value="石川県" {{ old('prefecture') == '石川県' ? 'selected' : '' }}>石川県</option>
                                <option value="福井県" {{ old('prefecture') == '福井県' ? 'selected' : '' }}>福井県</option>
                                <option value="山梨県" {{ old('prefecture') == '山梨県' ? 'selected' : '' }}>山梨県</option>
                                <option value="長野県" {{ old('prefecture') == '長野県' ? 'selected' : '' }}>長野県</option>
                                <option value="岐阜県" {{ old('prefecture') == '岐阜県' ? 'selected' : '' }}>岐阜県</option>
                                <option value="静岡県" {{ old('prefecture') == '静岡県' ? 'selected' : '' }}>静岡県</option>
                                <option value="愛知県" {{ old('prefecture') == '愛知県' ? 'selected' : '' }}>愛知県</option>
                                <option value="三重県" {{ old('prefecture') == '三重県' ? 'selected' : '' }}>三重県</option>
                                <option value="滋賀県" {{ old('prefecture') == '滋賀県' ? 'selected' : '' }}>滋賀県</option>
                                <option value="京都府" {{ old('prefecture') == '京都府' ? 'selected' : '' }}>京都府</option>
                                <option value="大阪府" {{ old('prefecture') == '大阪府' ? 'selected' : '' }}>大阪府</option>
                                <option value="兵庫県" {{ old('prefecture') == '兵庫県' ? 'selected' : '' }}>兵庫県</option>
                                <option value="奈良県" {{ old('prefecture') == '奈良県' ? 'selected' : '' }}>奈良県</option>
                                <option value="和歌山県" {{ old('prefecture') == '和歌山県' ? 'selected' : '' }}>和歌山県</option>
                                <option value="鳥取県" {{ old('prefecture') == '鳥取県' ? 'selected' : '' }}>鳥取県</option>
                                <option value="島根県" {{ old('prefecture') == '島根県' ? 'selected' : '' }}>島根県</option>
                                <option value="岡山県" {{ old('prefecture') == '岡山県' ? 'selected' : '' }}>岡山県</option>
                                <option value="広島県" {{ old('prefecture') == '広島県' ? 'selected' : '' }}>広島県</option>
                                <option value="山口県" {{ old('prefecture') == '山口県' ? 'selected' : '' }}>山口県</option>
                                <option value="徳島県" {{ old('prefecture') == '徳島県' ? 'selected' : '' }}>徳島県</option>
                                <option value="香川県" {{ old('prefecture') == '香川県' ? 'selected' : '' }}>香川県</option>
                                <option value="愛媛県" {{ old('prefecture') == '愛媛県' ? 'selected' : '' }}>愛媛県</option>
                                <option value="高知県" {{ old('prefecture') == '高知県' ? 'selected' : '' }}>高知県</option>
                                <option value="福岡県" {{ old('prefecture') == '福岡県' ? 'selected' : '' }}>福岡県</option>
                                <option value="佐賀県" {{ old('prefecture') == '佐賀県' ? 'selected' : '' }}>佐賀県</option>
                                <option value="長崎県" {{ old('prefecture') == '長崎県' ? 'selected' : '' }}>長崎県</option>
                                <option value="熊本県" {{ old('prefecture') == '熊本県' ? 'selected' : '' }}>熊本県</option>
                                <option value="大分県" {{ old('prefecture') == '大分県' ? 'selected' : '' }}>大分県</option>
                                <option value="宮崎県" {{ old('prefecture') == '宮崎県' ? 'selected' : '' }}>宮崎県</option>
                                <option value="鹿児島県" {{ old('prefecture') == '鹿児島県' ? 'selected' : '' }}>鹿児島県</option>
                                <option value="沖縄県" {{ old('prefecture') == '沖縄県' ? 'selected' : '' }}>沖縄県</option>
                            </select>
                            @error('prefecture')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="mb-6">
                            <label for="city" class="block text-sm font-medium text-gray-700 mb-2">
                                市区町村
                            </label>
                            <input type="text" name="city" id="city" value="{{ old('city') }}"
                                class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-blue-500 focus:border-blue-500 @error('city') border-red-500 @enderror">
                            @error('city')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex justify-end space-x-3">
                            <a href="{{ route('admin.areas.index') }}" class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-6 rounded">
                                キャンセル
                            </a>
                            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-6 rounded">
                                作成
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
