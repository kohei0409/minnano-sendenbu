<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('スタッフ編集') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('store.staff.update', $staff) }}">
                        @csrf
                        @method('PUT')

                        <!-- Username (Read-only) -->
                        <div>
                            <x-input-label for="username" :value="__('ユーザー名')" />
                            <x-text-input id="username" class="block mt-1 w-full bg-gray-100" type="text" :value="$staff->username" disabled />
                            <p class="mt-1 text-sm text-gray-600">ユーザー名は変更できません</p>
                        </div>

                        <!-- Name -->
                        <div class="mt-4">
                            <x-input-label for="name" :value="__('名前')" />
                            <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name', $staff->name)" required />
                            <x-input-error :messages="$errors->get('name')" class="mt-2" />
                        </div>

                        <!-- Email -->
                        <div class="mt-4">
                            <x-input-label for="email" :value="__('メールアドレス')" />
                            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email', $staff->email)" required />
                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password (Optional) -->
                        <div class="mt-4">
                            <x-input-label for="password" :value="__('新しいパスワード（変更する場合のみ）')" />
                            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" />
                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Password Confirmation -->
                        <div class="mt-4">
                            <x-input-label for="password_confirmation" :value="__('新しいパスワード（確認）')" />
                            <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" />
                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Permissions -->
                        <div class="mt-4">
                            <x-input-label :value="__('権限')" />
                            <div class="mt-2 grid grid-cols-2 gap-2">
                                @foreach($permissions as $permission)
                                    <label class="flex items-center">
                                        <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" 
                                            {{ $staff->hasPermissionTo($permission->name) ? 'checked' : '' }}
                                            class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                        <span class="ml-2 text-sm text-gray-700">{{ $permission->name }}</span>
                                    </label>
                                @endforeach
                            </div>
                            <x-input-error :messages="$errors->get('permissions')" class="mt-2" />
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('store.staff.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                キャンセル
                            </a>
                            <x-primary-button>
                                {{ __('更新') }}
                            </x-primary-button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
