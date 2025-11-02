<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('申請詳細') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if(session('success'))
                <div class="mb-4 bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
                    {!! nl2br(e(session('success'))) !!}
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold mb-4">申請情報</h3>

                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-600">申請ID</p>
                            <p class="font-semibold">{{ $application->id }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">ステータス</p>
                            <p class="font-semibold">
                                @if($application->status === 'pending')
                                    <span class="px-2 py-1 text-xs bg-yellow-100 text-yellow-800 rounded">承認待ち</span>
                                @elseif($application->status === 'approved')
                                    <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">承認済み</span>
                                @else
                                    <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">却下</span>
                                @endif
                            </p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">店舗名</p>
                            <p class="font-semibold">{{ $application->store_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">業種</p>
                            <p class="font-semibold">{{ $application->industry }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">担当者名</p>
                            <p class="font-semibold">{{ $application->contact_name }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">メールアドレス</p>
                            <p class="font-semibold">{{ $application->email }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">電話番号</p>
                            <p class="font-semibold">{{ $application->phone }}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">申請日時</p>
                            <p class="font-semibold">{{ $application->created_at->format('Y-m-d H:i') }}</p>
                        </div>
                    </div>

                    @if($application->message)
                        <div class="mb-6">
                            <p class="text-sm text-gray-600">メッセージ</p>
                            <p class="mt-1 p-4 bg-gray-50 rounded">{{ $application->message }}</p>
                        </div>
                    @endif

                    @if($application->status !== 'pending')
                        <div class="mb-6 p-4 bg-gray-50 rounded">
                            <p class="text-sm text-gray-600">審査情報</p>
                            <p class="mt-1"><strong>審査者:</strong> {{ $application->reviewer ? $application->reviewer->name : 'N/A' }}</p>
                            <p class="mt-1"><strong>審査日時:</strong> {{ $application->reviewed_at ? $application->reviewed_at->format('Y-m-d H:i') : 'N/A' }}</p>
                            @if($application->rejection_reason)
                                <p class="mt-1"><strong>却下理由:</strong> {{ $application->rejection_reason }}</p>
                            @endif
                        </div>
                    @endif

                    @if($application->isPending())
                        <div class="flex gap-4 mt-6">
                            <!-- Approve Form -->
                            <form method="POST" action="{{ route('admin.applications.approve', $application) }}" onsubmit="return confirm('この申請を承認しますか？店舗とオーナーアカウントが作成されます。');">
                                @csrf
                                <button type="submit" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                                    承認
                                </button>
                            </form>

                            <!-- Reject Form -->
                            <form method="POST" action="{{ route('admin.applications.reject', $application) }}" onsubmit="return confirmReject();" id="rejectForm">
                                @csrf
                                <input type="hidden" name="rejection_reason" id="rejection_reason">
                                <button type="button" onclick="showRejectDialog()" class="px-4 py-2 bg-red-600 text-white rounded-md hover:bg-red-700">
                                    却下
                                </button>
                            </form>

                            <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                戻る
                            </a>
                        </div>
                    @else
                        <div class="mt-6">
                            <a href="{{ route('admin.applications.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-md hover:bg-gray-700">
                                戻る
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <script>
        function showRejectDialog() {
            const reason = prompt('却下理由を入力してください:');
            if (reason !== null && reason.trim() !== '') {
                document.getElementById('rejection_reason').value = reason;
                document.getElementById('rejectForm').submit();
            }
        }

        function confirmReject() {
            return confirm('この申請を却下しますか？');
        }
    </script>
</x-app-layout>
