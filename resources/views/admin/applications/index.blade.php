<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('店舗申請管理') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <!-- Filter Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-4">
                <div class="p-6">
                    <form method="GET" action="{{ route('admin.applications.index') }}" class="flex gap-4">
                        <select name="status" class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm">
                            <option value="">全てのステータス</option>
                            <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>承認待ち</option>
                            <option value="approved" {{ request('status') == 'approved' ? 'selected' : '' }}>承認済み</option>
                            <option value="rejected" {{ request('status') == 'rejected' ? 'selected' : '' }}>却下</option>
                        </select>
                        <button type="submit" class="px-4 py-2 bg-gray-800 text-white rounded-md hover:bg-gray-700">
                            フィルター
                        </button>
                    </form>
                </div>
            </div>

            <!-- Applications Table -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead>
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ID</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">店舗名</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">業種</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">担当者</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">ステータス</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">申請日</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">操作</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @forelse($applications as $application)
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->id }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->store_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->industry }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->contact_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($application->status === 'pending')
                                            <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">承認待ち</span>
                                        @elseif($application->status === 'approved')
                                            <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">承認済み</span>
                                        @else
                                            <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">却下</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ $application->created_at->format('Y-m-d') }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <a href="{{ route('admin.applications.show', $application) }}" class="text-indigo-600 hover:text-indigo-900">
                                            詳細
                                        </a>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                        申請がありません
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                    <div class="mt-4">
                        {{ $applications->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
