<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('スタッフ管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            <div class="mb-4">
                <a href="{{ route('store.staff.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    新しいスタッフを追加
                </a>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ユーザー名</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">名前</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">メール</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">権限</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($staff as $member)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->username }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $member->email }}</td>
                                    <td class="px-6 py-4">
                                        <div class="flex flex-wrap gap-1">
                                            @forelse($member->permissions as $permission)
                                                <span class="px-2 py-1 text-xs bg-indigo-100 text-indigo-800 rounded">
                                                    {{ $permission->name }}
                                                </span>
                                            @empty
                                                <span class="text-gray-500 text-sm">なし</span>
                                            @endforelse
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('store.staff.edit', $member) }}" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            編集
                                        </a>
                                        <form method="POST" action="{{ route('store.staff.destroy', $member) }}" class="inline" onsubmit="return confirm('本当に削除しますか？');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-600 hover:text-red-900">
                                                削除
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                        スタッフがいません
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $staff->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
