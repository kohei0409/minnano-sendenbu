<x-layouts.public>
    <x-slot name="title">ログイン</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                会員ログイン
            </h2>
            <p class="mt-2 text-center text-sm text-gray-600">
                または
                <a href="{{ route('customer.register') }}" class="font-medium text-orange-600 hover:text-orange-500">
                    新規会員登録
                </a>
            </p>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-200">
                <form class="space-y-6" action="{{ route('customer.login') }}" method="POST">
                    @csrf

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">
                            メールアドレス
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
                            パスワード
                        </label>
                        <div class="mt-1">
                            <input id="password"
                                   name="password"
                                   type="password"
                                   autocomplete="current-password"
                                   required
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('password') border-red-500 @enderror">
                        </div>
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Remember Me -->
                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember"
                                   name="remember"
                                   type="checkbox"
                                   class="h-4 w-4 text-orange-600 focus:ring-orange-500 border-gray-300 rounded">
                            <label for="remember" class="ml-2 block text-sm text-gray-900">
                                ログイン状態を保持する
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="{{ route('customer.password.request') }}" class="font-medium text-orange-600 hover:text-orange-500">
                                パスワードをお忘れですか？
                            </a>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div>
                        <button type="submit"
                                class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-bold text-white bg-gradient-to-r from-orange-500 to-orange-600 hover:from-orange-600 hover:to-orange-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-orange-500 transition">
                            ログイン
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
