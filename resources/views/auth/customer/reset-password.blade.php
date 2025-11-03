<x-layouts.public>
    <x-slot name="title">パスワードリセット</x-slot>

    <div class="min-h-screen bg-gray-50 flex flex-col justify-center py-12 sm:px-6 lg:px-8">
        <div class="sm:mx-auto sm:w-full sm:max-w-md">
            <h2 class="mt-6 text-center text-3xl font-bold text-gray-900">
                新しいパスワードを設定
            </h2>
        </div>

        <div class="mt-8 sm:mx-auto sm:w-full sm:max-w-md">
            <div class="bg-white py-8 px-4 shadow-lg sm:rounded-lg sm:px-10 border border-gray-200">
                <form class="space-y-6" action="{{ route('customer.password.update') }}" method="POST">
                    @csrf

                    <input type="hidden" name="token" value="{{ $token }}">

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
                                   readonly
                                   value="{{ $email ?? old('email') }}"
                                   class="appearance-none block w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm placeholder-gray-400 bg-gray-50 focus:outline-none focus:ring-orange-500 focus:border-orange-500 @error('email') border-red-500 @enderror">
                        </div>
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">
                            新しいパスワード <span class="text-red-600">*</span>
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
                            パスワードをリセット
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-layouts.public>
