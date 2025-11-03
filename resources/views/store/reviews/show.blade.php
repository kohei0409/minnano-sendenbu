<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                レビュー詳細・返信
            </h2>
            <a href="{{ route('store.reviews.index') }}" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded transition">
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

            <!-- Review Detail -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                <div class="p-6 border-b border-gray-200">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">レビュー内容</h3>

                    <!-- Customer Info -->
                    <div class="bg-gray-50 p-4 rounded-lg mb-6">
                        <div class="flex items-center justify-between mb-3">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-orange-100 rounded-full p-2">
                                    <svg class="h-6 w-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm font-medium text-gray-900">{{ $review->customer->user->name ?? '匿名' }}</p>
                                    <p class="text-xs text-gray-500">{{ $review->created_at->format('Y年m月d日 H:i') }}</p>
                                </div>
                            </div>

                            <!-- Status Badge -->
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

                        @if($review->visit_date)
                            <div class="flex items-center text-sm text-gray-600">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                                <span>訪問日: {{ $review->visit_date->format('Y年m月d日') }}</span>
                            </div>
                        @endif
                    </div>

                    <!-- Rating -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">評価</h4>
                        <div class="flex items-center">
                            @for ($i = 1; $i <= 5; $i++)
                                @if ($i <= $review->rating)
                                    <svg class="w-8 h-8 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @else
                                    <svg class="w-8 h-8 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                        <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                    </svg>
                                @endif
                            @endfor
                            <span class="ml-3 text-xl font-semibold text-gray-900">{{ $review->rating }}/5</span>
                        </div>
                    </div>

                    <!-- Review Title -->
                    @if($review->title)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">タイトル</h4>
                            <p class="text-lg font-bold text-gray-900">{{ $review->title }}</p>
                        </div>
                    @endif

                    <!-- Review Content -->
                    <div class="mb-6">
                        <h4 class="text-sm font-medium text-gray-700 mb-2">レビュー内容</h4>
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <p class="text-base text-gray-900 whitespace-pre-wrap">{{ $review->content }}</p>
                        </div>
                    </div>

                    <!-- Review Images -->
                    @if($review->images->count() > 0)
                        <div class="mb-6">
                            <h4 class="text-sm font-medium text-gray-700 mb-2">投稿画像</h4>
                            <div class="grid grid-cols-2 md:grid-cols-3 gap-4">
                                @foreach($review->images as $image)
                                    <div class="aspect-square rounded-lg overflow-hidden bg-gray-100">
                                        <img src="{{ asset('storage/' . $image->image_path) }}"
                                             alt="レビュー画像"
                                             class="w-full h-full object-cover hover:opacity-90 transition cursor-pointer"
                                             onclick="window.open(this.src, '_blank')">
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Existing Reply (if exists) -->
            @if($review->reply)
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-6 bg-green-50 border-l-4 border-green-500">
                        <div class="flex items-start">
                            <div class="flex-shrink-0">
                                <svg class="h-6 w-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                </svg>
                            </div>
                            <div class="ml-3 flex-1">
                                <h3 class="text-lg font-semibold text-gray-900 mb-2">返信済み</h3>
                                <div class="text-sm text-gray-600 mb-3">
                                    <span class="font-medium">{{ $review->reply->user->name }}</span>
                                    <span class="mx-2">•</span>
                                    <span>{{ $review->reply->created_at->format('Y年m月d日 H:i') }}</span>
                                </div>
                                <div class="bg-white p-4 rounded-lg">
                                    <p class="text-base text-gray-900 whitespace-pre-wrap">{{ $review->reply->content }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Reply Form -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 mb-4">
                        @if($review->reply)
                            返信を編集
                        @else
                            レビューに返信する
                        @endif
                    </h3>

                    <form action="{{ $review->reply ? route('store.reviews.reply.update', $review) : route('store.reviews.reply.store', $review) }}"
                          method="POST">
                        @csrf
                        @if($review->reply)
                            @method('PUT')
                        @endif

                        <div class="mb-6">
                            <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                                返信内容
                                <span class="text-red-500">*</span>
                            </label>
                            <textarea
                                id="content"
                                name="content"
                                rows="6"
                                class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-transparent @error('content') border-red-500 @enderror"
                                placeholder="お客様のレビューに対して丁寧に返信してください。&#10;&#10;例：&#10;この度はご来店いただき、誠にありがとうございます。&#10;また、素敵なレビューをいただき大変嬉しく思います。&#10;これからもお客様にご満足いただけるよう、スタッフ一同精進してまいります。&#10;またのご来店を心よりお待ちしております。"
                                required>{{ old('content', $review->reply->content ?? '') }}</textarea>
                            @error('content')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                            <p class="mt-2 text-sm text-gray-500">
                                お客様への感謝の気持ちと、今後も良いサービスを提供する意欲を伝えましょう。
                            </p>
                        </div>

                        <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                            <div class="text-sm text-gray-600">
                                <svg class="inline-block w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                返信は公開ページに表示されます
                            </div>
                            <div class="flex gap-3">
                                <a href="{{ route('store.reviews.index') }}"
                                   class="px-6 py-3 bg-gray-200 hover:bg-gray-300 text-gray-800 font-medium rounded-lg transition">
                                    キャンセル
                                </a>
                                <button type="submit"
                                        class="px-6 py-3 bg-orange-600 hover:bg-orange-700 text-white font-medium rounded-lg transition flex items-center">
                                    @if($review->reply)
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                        返信を更新
                                    @else
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/>
                                        </svg>
                                        返信を送信
                                    @endif
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Delete Reply Button (if reply exists) -->
            @if($review->reply)
                <div class="mt-4 text-right">
                    <form action="{{ route('store.reviews.reply.destroy', $review) }}"
                          method="POST"
                          class="inline-block"
                          onsubmit="return confirm('本当に返信を削除しますか? この操作は取り消せません。');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                class="text-sm text-red-600 hover:text-red-700 font-medium underline">
                            返信を削除
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
