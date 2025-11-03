<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                レビュー詳細
            </h2>
            <a href="{{ route('admin.reviews.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                一覧に戻る
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif

            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 bg-white border-b border-gray-200">
                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">ステータス</h3>
                        @if ($review->status === 'pending')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                承認待ち
                            </span>
                        @elseif ($review->status === 'published')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                公開中
                            </span>
                        @elseif ($review->status === 'rejected')
                            <span class="px-3 py-1 inline-flex text-sm leading-5 font-semibold rounded-full bg-red-100 text-red-800">
                                却下
                            </span>
                        @endif
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">店舗情報</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">店舗名</p>
                            <p class="text-base font-medium text-gray-900 mb-2">{{ $review->store->name ?? 'N/A' }}</p>
                            @if ($review->store)
                                <p class="text-sm text-gray-600">住所</p>
                                <p class="text-base text-gray-900">{{ $review->store->address ?? 'N/A' }}</p>
                            @endif
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">投稿者情報</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-sm text-gray-600">名前</p>
                            <p class="text-base font-medium text-gray-900 mb-2">{{ $review->user->name ?? 'N/A' }}</p>
                            <p class="text-sm text-gray-600">メールアドレス</p>
                            <p class="text-base text-gray-900">{{ $review->user->email ?? 'N/A' }}</p>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">評価</h3>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <svg class="w-6 h-6 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-6 h-6 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-2 text-lg font-semibold text-gray-900">{{ $review->rating }}/5</span>
                        </div>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">タイトル</h3>
                        <p class="text-base text-gray-900">{{ $review->title }}</p>
                    </div>

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">レビュー内容</h3>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-base text-gray-900 whitespace-pre-wrap">{{ $review->content }}</p>
                        </div>
                    </div>

                    @if ($review->visit_date)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">訪問日</h3>
                            <p class="text-base text-gray-900">{{ $review->visit_date }}</p>
                        </div>
                    @endif

                    @if ($review->images && count($review->images) > 0)
                        <div class="mb-6">
                            <h3 class="text-lg font-semibold text-gray-900 mb-4">画像</h3>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach ($review->images as $image)
                                    <div class="aspect-square">
                                        <img src="{{ $image }}" alt="レビュー画像" class="w-full h-full object-cover rounded-lg">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 mb-4">投稿日時</h3>
                        <p class="text-base text-gray-900">{{ $review->created_at->format('Y年m月d日 H:i') }}</p>
                    </div>

                    <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
                        @if ($review->status === 'pending')
                            <form action="{{ route('admin.reviews.reject', $review) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-orange-600 hover:bg-orange-700 text-white font-bold py-2 px-6 rounded" onclick="return confirm('このレビューを却下しますか?');">
                                    却下
                                </button>
                            </form>
                            <form action="{{ route('admin.reviews.approve', $review) }}" method="POST" class="inline-block">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-6 rounded" onclick="return confirm('このレビューを承認しますか?');">
                                    承認
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.reviews.destroy', $review) }}" method="POST" class="inline-block">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="bg-red-600 hover:bg-red-700 text-white font-bold py-2 px-6 rounded" onclick="return confirm('本当に削除しますか? この操作は取り消せません。');">
                                削除
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
