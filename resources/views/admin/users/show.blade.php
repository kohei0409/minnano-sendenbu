<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ユーザー詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">基本情報</h3>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">ID</p>
                            <p class="font-semibold">{{ $user->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">ユーザー名</p>
                            <p class="font-semibold">{{ $user->username }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">名前</p>
                            <p class="font-semibold">{{ $user->name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">メールアドレス</p>
                            <p class="font-semibold">{{ $user->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">役割</p>
                            <p class="font-semibold">{{ $user->role }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">店舗</p>
                            <p class="font-semibold">{{ $user->store ? $user->store->store_name : 'なし' }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">登録日時</p>
                            <p class="font-semibold">{{ $user->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">承認日時</p>
                            <p class="font-semibold">{{ $user->approved_at ? $user->approved_at->format('Y-m-d H:i') : 'N/A' }}</p>
                        </div>
                    </div>

                    @if($user->permissions->count() > 0)
                        <div class="mb-6">
                            <p class="text-sm text-gray-600 mb-2">権限</p>
                            <div class="flex flex-wrap gap-2">
                                @foreach($user->permissions as $permission)
                                    <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded">
                                        {{ $permission->name }}
                                    </span>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Update User Level -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">ユーザーレベル管理</h3>
                    <p class="text-sm text-gray-600 mb-4">現在のレベル: <span class="font-semibold">{{ $user->user_level }}</span></p>
                    
                    <form method="POST" action="{{ route('admin.users.update-level', $user) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <select name="user_level" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="free" {{ $user->user_level === 'free' ? 'selected' : '' }}>Free</option>
                                    <option value="premium1" {{ $user->user_level === 'premium1' ? 'selected' : '' }}>Premium 1</option>
                                    <option value="premium2" {{ $user->user_level === 'premium2' ? 'selected' : '' }}>Premium 2</option>
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                レベル更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Update User Status -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">ステータス管理</h3>
                    <p class="text-sm text-gray-600 mb-4">現在のステータス: <span class="font-semibold">{{ $user->status }}</span></p>
                    
                    <form method="POST" action="{{ route('admin.users.update-status', $user) }}">
                        @csrf
                        @method('PATCH')
                        
                        <div class="flex gap-4 items-end">
                            <div class="flex-1">
                                <select name="status" class="block w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                                    <option value="pending" {{ $user->status === 'pending' ? 'selected' : '' }}>承認待ち</option>
                                    <option value="active" {{ $user->status === 'active' ? 'selected' : '' }}>有効</option>
                                    <option value="suspended" {{ $user->status === 'suspended' ? 'selected' : '' }}>停止</option>
                                </select>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                ステータス更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="mt-6">
                <a href="{{ route('admin.users.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                    戻る
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
