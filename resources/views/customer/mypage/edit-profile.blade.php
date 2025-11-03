<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            プロフィール編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <!-- 成功メッセージ -->
                    @if(session('success'))
                        <div class="mb-6 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                {{ session('success') }}
                            </div>
                        </div>
                    @endif

                    <form action="{{ route('customer.mypage.update-profile') }}" method="POST">
                        @csrf
                        @method('PUT')

                        <!-- 基本情報 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-orange-500">
                                基本情報
                            </h3>

                            <!-- 名前 -->
                            <div class="mb-4">
                                <label for="name" class="block text-sm font-medium text-gray-700 mb-2">
                                    お名前 <span class="text-red-500">*</span>
                                </label>
                                <input type="text"
                                       name="name"
                                       id="name"
                                       value="{{ old('name', Auth::user()->name) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('name') border-red-500 @enderror">
                                @error('name')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- メールアドレス -->
                            <div class="mb-4">
                                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                                    メールアドレス <span class="text-red-500">*</span>
                                </label>
                                <input type="email"
                                       name="email"
                                       id="email"
                                       value="{{ old('email', Auth::user()->email) }}"
                                       required
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('email') border-red-500 @enderror">
                                @error('email')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 電話番号 -->
                            <div class="mb-4">
                                <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">
                                    電話番号
                                </label>
                                <input type="tel"
                                       name="phone"
                                       id="phone"
                                       value="{{ old('phone', Auth::user()->phone) }}"
                                       placeholder="例: 090-1234-5678"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('phone') border-red-500 @enderror">
                                @error('phone')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 生年月日 -->
                            <div class="mb-4">
                                <label for="birth_date" class="block text-sm font-medium text-gray-700 mb-2">
                                    生年月日
                                </label>
                                <input type="date"
                                       name="birth_date"
                                       id="birth_date"
                                       value="{{ old('birth_date', Auth::user()->birth_date ? Auth::user()->birth_date->format('Y-m-d') : '') }}"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('birth_date') border-red-500 @enderror">
                                @error('birth_date')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 性別 -->
                            <div class="mb-4">
                                <label class="block text-sm font-medium text-gray-700 mb-2">
                                    性別
                                </label>
                                <div class="flex gap-4">
                                    <label class="inline-flex items-center">
                                        <input type="radio"
                                               name="gender"
                                               value="male"
                                               {{ old('gender', Auth::user()->gender) === 'male' ? 'checked' : '' }}
                                               class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                        <span class="ml-2">男性</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio"
                                               name="gender"
                                               value="female"
                                               {{ old('gender', Auth::user()->gender) === 'female' ? 'checked' : '' }}
                                               class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                        <span class="ml-2">女性</span>
                                    </label>
                                    <label class="inline-flex items-center">
                                        <input type="radio"
                                               name="gender"
                                               value="other"
                                               {{ old('gender', Auth::user()->gender) === 'other' ? 'checked' : '' }}
                                               class="w-4 h-4 text-orange-500 border-gray-300 focus:ring-orange-500">
                                        <span class="ml-2">その他</span>
                                    </label>
                                </div>
                                @error('gender')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- 住所情報 -->
                        <div class="mb-6">
                            <h3 class="text-lg font-bold text-gray-900 mb-4 pb-2 border-b-2 border-blue-500">
                                住所情報
                            </h3>

                            <!-- 郵便番号 -->
                            <div class="mb-4">
                                <label for="postal_code" class="block text-sm font-medium text-gray-700 mb-2">
                                    郵便番号
                                </label>
                                <input type="text"
                                       name="postal_code"
                                       id="postal_code"
                                       value="{{ old('postal_code', Auth::user()->postal_code) }}"
                                       placeholder="例: 123-4567"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('postal_code') border-red-500 @enderror">
                                @error('postal_code')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>

                            <!-- 住所 -->
                            <div class="mb-4">
                                <label for="address" class="block text-sm font-medium text-gray-700 mb-2">
                                    住所
                                </label>
                                <input type="text"
                                       name="address"
                                       id="address"
                                       value="{{ old('address', Auth::user()->address) }}"
                                       placeholder="例: 東京都渋谷区1-2-3"
                                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent @error('address') border-red-500 @enderror">
                                @error('address')
                                    <p class="mt-1 text-sm text-red-500">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>

                        <!-- ボタン -->
                        <div class="flex flex-col sm:flex-row gap-3 justify-end pt-6 border-t">
                            <a href="{{ route('customer.mypage.index') }}"
                               class="px-6 py-3 bg-gray-200 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-300 transition">
                                キャンセル
                            </a>
                            <button type="submit"
                                    class="px-6 py-3 bg-orange-500 text-white font-bold rounded-lg hover:bg-orange-600 transition">
                                保存する
                            </button>
                        </div>
                    </form>

                    <!-- パスワード変更リンク -->
                    <div class="mt-6 pt-6 border-t">
                        <a href="{{ route('customer.mypage.edit-password') }}"
                           class="inline-flex items-center text-blue-600 hover:text-blue-700 font-medium">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m4 0a6 6 0 01-7.743 5.743L11 17H9v2H7v2H4a1 1 0 01-1-1v-2.586a1 1 0 01.293-.707l5.964-5.964A6 6 0 1121 9z"/>
                            </svg>
                            パスワードを変更する
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
