<x-layouts.public>
    <x-slot name="title">会員登録</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                新規会員登録
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                すでに会員の方は
                <a href="{{ route('customer.login') }}" class="font-medium text-orange-600 hover:text-orange-500">
                    ログイン
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-200">
                <!-- Benefits -->
                <div class="mb-6 bg-orange-50 border border-orange-200 rounded-lg p-4">
                    <h3 class="text-sm font-bold text-orange-900 mb-2">会員登録のメリット</h3>
                    <ul class="text-sm text-orange-800 space-y-1">
                        <li>✓ レビューの投稿</li>
                        <li>✓ お気に入り店舗の保存</li>
                        <li>✓ ネット予約の利用</li>
                        <li>✓ クーポンの取得</li>
                    </ul>
                </div>

                <form class="space-y-6" action="{{ route('customer.register') }}" method="POST">
                    @csrf

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">
                            お名前 <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="name"
                                   name="name"
                                   type="text"
                                   autocomplete="name"
                                   required
                                   value="{{ old('name') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
                        </div>
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            メールアドレス <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="email"
                                   name="email"
                                   type="email"
                                   autocomplete="email"
                                   required
                                   value="{{ old('email') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            パスワード <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="new-password"
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror">
                        </div>
                        <p class="mt-1 text-xs text-gray-500">8文字以上で入力してください</p>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Password Confirmation -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">
                            パスワード（確認） <span class="text-red-600">*</span>
                        </label>
                        <div class="mt-1">
                            <input id="password_confirmation"
                                   name="password_confirmation"
                                   type="password"
                                   autocomplete="new-password"
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500">
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                            会員登録する
                        </button>
                    </div>
                </form>

                <div class="mt-6">
                    <div class="relative">
                        <div class="absolute inset-0 flex items-center">
                            <div class="w-full border-t border-gray-300"></div>
                        </div>
                        <div class="relative flex justify-center text-sm">
                            <span class="px-2 bg-white text-gray-500">
                                または
                            </span>
                        </div>
                    </div>

                    <div class="mt-6">
                        <a href="{{ route('stores.index') }}"
                           class="w-full inline-flex justify-center py-3 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                            会員登録せずに利用する
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-layouts.public>
