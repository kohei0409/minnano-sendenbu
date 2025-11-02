<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('ユーザー管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Filter Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-wrap gap-4">
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="名前、ユーザー名、メールで検索" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                        
                        <select name="role" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">全ての役割</option>
                            <option value="admin" {{ request('role') == 'admin' ? 'selected' : '' }}>管理者</option>
                            <option value="store_owner" {{ request('role') == 'store_owner' ? 'selected' : '' }}>店舗オーナー</option>
                            <option value="store_staff" {{ request('role') == 'store_staff' ? 'selected' : '' }}>スタッフ</option>
                        </select>
                        
                        <select name="user_level" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">全てのレベル</option>
                            <option value="free" {{ request('user_level') == 'free' ? 'selected' : '' }}>Free</option>
                            <option value="premium1" {{ request('user_level') == 'premium1' ? 'selected' : '' }}>Premium 1</option>
                            <option value="premium2" {{ request('user_level') == 'premium2' ? 'selected' : '' }}>Premium 2</option>
                        </select>
                        
                        <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">全てのステータス</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>承認待ち</option>
                            <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>有効</option>
                            <option value="suspended" {{ request('status') == 'suspended' ? 'selected' : '' }}>停止</option>
                        </select>
                        
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                            フィルター
                        </button>
                    </form>
                </div>
            </div>

            <!-- Users Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead>
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ユーザー名</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">名前</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">メール</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">役割</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">レベル</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">店舗</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                @forelse($users as $user)
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->id }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->username }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->name }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">{{ $user->email }}</td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->role === 'admin')
                                                <span class="px-2 py-1 text-xs bg-purple-100 text-purple-800 rounded">管理者</span>
                                            @elseif($user->role === 'store_owner')
                                                <span class="px-2 py-1 text-xs bg-blue-100 text-blue-800 rounded">オーナー</span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-gray-100 text-gray-800 rounded">スタッフ</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">{{ $user->user_level }}</span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            @if($user->status === 'active')
                                                <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">有効</span>
                                            @elseif($user->status === 'pending')
                                                <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">承認待ち</span>
                                            @else
                                                <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">停止</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            {{ $user->store ? $user->store->store_name : '-' }}
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <a href="{{ route('admin.users.show', $user) }}" class="text-indigo-600 hover:text-indigo-900">
                                                詳細
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                            ユーザーがいません
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4">
                        {{ $users->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
