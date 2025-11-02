<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('スタッフ詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">基本情報</h3>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">ID</p>
                            <p class="font-semibold">{{ $staff->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">ユーザー名</p>
                            <p class="font-semibold">{{ $staff->username }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">名前</p>
                            <p class="font-semibold">{{ $staff->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">メールアドレス</p>
                            <p class="font-semibold">{{ $staff->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">ユーザーレベル</p>
                            <p class="font-semibold">{{ $staff->user_level }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">ステータス</p>
                            <p class="font-semibold">{{ $staff->status }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">登録日時</p>
                            <p class="font-semibold">{{ $staff->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">承認日時</p>
                            <p class="font-semibold">{{ $staff->approved_at ? $staff->approved_at->format('Y-m-d H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($staff->permissions->count() > 0)
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-2">権限</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($staff->permissions as $permission)
                                    <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-2">権限</p>
                            <p class="text-gray-500">権限が割り当てられていません</p>
                        </div>
                    @endif

                    <div class="flex gap-4">
                        <a href="{{ route('store.staff.edit', $staff) }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                            編集
                        </a>
                        <a href="{{ route('store.staff.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                            戻る
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
