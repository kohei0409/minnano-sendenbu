<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            クーポン編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('store.coupons.update', $coupon) }}">
                        @csrf
                        @method('PUT')

                        <!-- Title -->
                        <div>
                            <label for="title" class="block text-sm font-medium text-gray-700">タイトル <span class="text-red-500">*</span></label>
                            <input id="title" type="text" name="title" value="{{ old('title', $coupon->title) }}" required autofocus
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror"
                                placeholder="例: 新規会員限定クーポン">
                            @error('title')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror"
                                placeholder="クーポンの詳細説明">{{ old('description', $coupon->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Type -->
                        <div class="mt-4">
                            <label for="discount_type" class="block text-sm font-medium text-gray-700">割引タイプ <span class="text-red-500">*</span></label>
                            <select id="discount_type" name="discount_type" required
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('discount_type') border-red-500 @enderror">
                                <option value="">選択してください</option>
                                <option value="percentage" {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'selected' : '' }}>パーセント割引</option>
                                <option value="amount" {{ old('discount_type', $coupon->discount_type) === 'amount' ? 'selected' : '' }}>金額割引</option>
                                <option value="free_item" {{ old('discount_type', $coupon->discount_type) === 'free_item' ? 'selected' : '' }}>無料商品</option>
                            </select>
                            @error('discount_type')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Discount Value -->
                        <div class="mt-4">
                            <label for="discount_value" class="block text-sm font-medium text-gray-700">割引値 <span class="text-red-500">*</span></label>
                            <input id="discount_value" type="number" name="discount_value" value="{{ old('discount_value', $coupon->discount_value) }}" required min="0" step="0.01"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('discount_value') border-red-500 @enderror"
                                placeholder="パーセントの場合は1-100、金額の場合は金額を入力">
                            <p class="mt-1 text-xs text-gray-500">パーセント割引の場合は1-100、金額割引の場合は金額を入力してください</p>
                            @error('discount_value')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Code (readonly) -->
                        <div class="mt-4">
                            <label for="code" class="block text-sm font-medium text-gray-700">クーポンコード</label>
                            <input id="code" type="text" value="{{ $coupon->code }}" readonly
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md bg-gray-100 text-gray-600 cursor-not-allowed">
                            <p class="mt-1 text-xs text-gray-500">クーポンコードは変更できません</p>
                        </div>

                        <!-- Usage Limit -->
                        <div class="mt-4">
                            <label for="usage_limit" class="block text-sm font-medium text-gray-700">使用回数制限</label>
                            <input id="usage_limit" type="number" name="usage_limit" value="{{ old('usage_limit', $coupon->usage_limit) }}" min="1"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('usage_limit') border-red-500 @enderror"
                                placeholder="空欄の場合は無制限">
                            <p class="mt-1 text-xs text-gray-500">空欄にすると使用回数が無制限になります (現在の使用回数: {{ $coupon->used_count }})</p>
                            @error('usage_limit')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Start Date -->
                        <div class="mt-4">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">開始日 <span class="text-red-500">*</span></label>
                            <input id="start_date" type="date" name="start_date" value="{{ old('start_date', $coupon->start_date->format('Y-m-d')) }}" required
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('start_date') border-red-500 @enderror">
                            @error('start_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- End Date -->
                        <div class="mt-4">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">終了日 <span class="text-red-500">*</span></label>
                            <input id="end_date" type="date" name="end_date" value="{{ old('end_date', $coupon->end_date->format('Y-m-d')) }}" required
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('end_date') border-red-500 @enderror">
                            @error('end_date')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Conditions -->
                        <div class="mt-4">
                            <label for="conditions" class="block text-sm font-medium text-gray-700">利用条件</label>
                            <textarea id="conditions" name="conditions" rows="3"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('conditions') border-red-500 @enderror"
                                placeholder="例: 5,000円以上のご利用で使用可能">{{ old('conditions', $coupon->conditions) }}</textarea>
                            @error('conditions')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Active -->
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_active" value="1" {{ old('is_active', $coupon->is_active) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">有効にする</span>
                            </label>
                            @error('is_active')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('store.coupons.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
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
    </div>
</x-app-layout>
