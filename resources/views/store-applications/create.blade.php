<x-guest-layout>
    <div class="max-w-2xl mx-auto">
        <h2 class="text-2xl font-bold mb-6">店舗登録申請</h2>

        <form method="POST" action="{{ route('store-applications.store') }}">
            @csrf

            <!-- Store Name -->
            <div>
                <x-input-label for="store_name" :value="__('店舗名')" />
                <x-text-input id="store_name" class="block mt-1 w-full" type="text" name="store_name" :value="old('store_name')" required autofocus />
                <x-input-error :messages="$errors->get('store_name')" class="mt-2" />
            </div>

            <!-- Industry -->
            <div class="mt-4">
                <x-input-label for="industry" :value="__('業種')" />
                <select id="industry" name="industry" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" required>
                    <option value="">選択してください</option>
                    @foreach($industries as $category => $types)
                        <optgroup label="{{ $category }}">
                            @foreach($types as $type)
                                <option value="{{ $type }}" {{ old('industry') == $type ? 'selected' : '' }}>{{ $type }}</option>
                            @endforeach
                        </optgroup>
                    @endforeach
                </select>
                <x-input-error :messages="$errors->get('industry')" class="mt-2" />
            </div>

            <!-- Contact Name -->
            <div class="mt-4">
                <x-input-label for="contact_name" :value="__('担当者名')" />
                <x-text-input id="contact_name" class="block mt-1 w-full" type="text" name="contact_name" :value="old('contact_name')" required />
                <x-input-error :messages="$errors->get('contact_name')" class="mt-2" />
            </div>

            <!-- Email -->
            <div class="mt-4">
                <x-input-label for="email" :value="__('メールアドレス')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone -->
            <div class="mt-4">
                <x-input-label for="phone" :value="__('電話番号')" />
                <x-text-input id="phone" class="block mt-1 w-full" type="tel" name="phone" :value="old('phone')" required />
                <x-input-error :messages="$errors->get('phone')" class="mt-2" />
            </div>

            <!-- Message -->
            <div class="mt-4">
                <x-input-label for="message" :value="__('メッセージ（任意）')" />
                <textarea id="message" name="message" rows="4" class="block mt-1 w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">{{ old('message') }}</textarea>
                <x-input-error :messages="$errors->get('message')" class="mt-2" />
            </div>

            <div class="flex items-center justify-end mt-6">
                <x-primary-button>
                    {{ __('申請を送信') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
