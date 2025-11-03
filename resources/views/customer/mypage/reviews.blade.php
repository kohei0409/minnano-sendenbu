<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            レビュー履歴
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- ステータスフィルター -->
            <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
                <div class="flex flex-wrap gap-2">
                    <a href="{{ route('customer.mypage.reviews') }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ !request('status') ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        全て ({{ Auth::user()->reviews()->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reviews', ['status' => 'pending']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'pending' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        承認待ち ({{ Auth::user()->reviews()->where('status', 'pending')->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reviews', ['status' => 'published']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'published' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        公開中 ({{ Auth::user()->reviews()->where('status', 'published')->count() }})
                    </a>
                    <a href="{{ route('customer.mypage.reviews', ['status' => 'rejected']) }}"
                       class="px-4 py-2 rounded-lg font-medium transition {{ request('status') === 'rejected' ? 'bg-blue-500 text-white' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                        却下 ({{ Auth::user()->reviews()->where('status', 'rejected')->count() }})
                    </a>
                </div>
            </div>

            <!-- レビュー一覧 -->
            @if($reviews->count() > 0)
                <div class="space-y-4 mb-6">
                    @foreach($reviews as $review)
                        <div class="bg-white rounded-lg shadow-sm overflow-hidden hover:shadow-md transition">
                            <div class="p-6">
                                <div class="flex flex-col md:flex-row md:items-start gap-4">
                                    <!-- 店舗情報 -->
                                    <div class="flex-1">
                                        <div class="flex items-start justify-between mb-3">
                                            <div class="flex-1">
                                                <h3 class="text-lg font-bold text-gray-900 mb-2">
                                                    <a href="{{ route('stores.show', $review->store) }}" class="hover:text-blue-600 transition">
                                                        {{ $review->store->store_name }}
                                                    </a>
                                                </h3>
                                                <div class="flex items-center gap-3 mb-2">
                                                    <!-- 評価 -->
                                                    <div class="flex text-yellow-400">
                                                        @for($i = 1; $i <= 5; $i++)
                                                            @if($i <= $review->rating)
                                                                <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @else
                                                                <svg class="w-5 h-5 text-gray-300 fill-current" viewBox="0 0 20 20">
                                                                    <path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/>
                                                                </svg>
                                                            @endif
                                                        @endfor
                                                    </div>
                                                    <!-- 投稿日 -->
                                                    <span class="text-sm text-gray-500">
                                                        {{ $review->created_at->format('Y年m月d日') }}
                                                    </span>
                                                </div>
                                            </div>

                                            <!-- ステータスバッジ -->
                                            <div class="flex flex-col gap-2 items-end">
                                                @if($review->status === 'pending')
                                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                                        承認待ち
                                                    </span>
                                                @elseif($review->status === 'published')
                                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-green-100 text-green-800">
                                                        公開中
                                                    </span>
                                                @elseif($review->status === 'rejected')
                                                    <span class="px-3 py-1 text-sm font-semibold rounded-full bg-red-100 text-red-800">
                                                        却下
                                                    </span>
                                                @endif

                                                @if($review->response_comment)
                                                    <span class="px-3 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        返信あり
                                                    </span>
                                                @endif
                                            </div>
                                        </div>

                                        <!-- レビュー本文 -->
                                        <div class="mb-3">
                                            @if($review->title)
                                                <h4 class="font-bold text-gray-800 mb-1">{{ $review->title }}</h4>
                                            @endif
                                            <p class="text-gray-700 leading-relaxed">{{ $review->comment }}</p>
                                        </div>

                                        <!-- 店舗からの返信 -->
                                        @if($review->response_comment)
                                            <div class="mt-3 p-4 bg-blue-50 rounded-lg border border-blue-200">
                                                <div class="flex items-center mb-2">
                                                    <svg class="w-5 h-5 text-blue-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                                    </svg>
                                                    <span class="font-bold text-blue-900">店舗からの返信</span>
                                                    @if($review->response_date)
                                                        <span class="ml-2 text-sm text-blue-600">
                                                            ({{ $review->response_date->format('Y/m/d') }})
                                                        </span>
                                                    @endif
                                                </div>
                                                <p class="text-gray-700">{{ $review->response_comment }}</p>
                                            </div>
                                        @endif

                                        <!-- 却下理由 -->
                                        @if($review->status === 'rejected' && $review->rejection_reason)
                                            <div class="mt-3 p-4 bg-red-50 rounded-lg border border-red-200">
                                                <p class="font-bold text-red-900 mb-1">却下理由:</p>
                                                <p class="text-gray-700">{{ $review->rejection_reason }}</p>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- アクションボタン -->
                                    <div class="flex flex-row md:flex-col gap-2">
                                        @if($review->status === 'pending' || $review->status === 'rejected')
                                            <a href="{{ route('customer.reviews.edit', $review) }}"
                                               class="px-4 py-2 bg-blue-500 text-white text-center font-medium rounded-lg hover:bg-blue-600 transition whitespace-nowrap">
                                                編集
                                            </a>
                                        @endif

                                        <form action="{{ route('customer.reviews.destroy', $review) }}"
                                              method="POST"
                                              onsubmit="return confirm('このレビューを削除してもよろしいですか?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                    class="w-full px-4 py-2 bg-red-100 text-red-700 font-medium rounded-lg hover:bg-red-200 transition whitespace-nowrap">
                                                削除
                                            </button>
                                        </form>

                                        <a href="{{ route('stores.show', $review->store) }}"
                                           class="px-4 py-2 bg-gray-100 text-gray-700 text-center font-medium rounded-lg hover:bg-gray-200 transition whitespace-nowrap">
                                            店舗詳細
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                <!-- ページネーション -->
                <div class="mt-6">
                    {{ $reviews->links() }}
                </div>
            @else
                <!-- 空の状態 -->
                <div class="bg-white rounded-lg shadow-sm p-12 text-center">
                    <svg class="w-24 h-24 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">レビュー履歴がありません</h3>
                    <p class="text-gray-600 mb-6">訪問した店舗のレビューを投稿してみましょう</p>
                    <a href="{{ route('customer.mypage.reservations', ['status' => 'completed']) }}"
                       class="inline-block px-6 py-3 bg-blue-500 text-white font-bold rounded-lg hover:bg-blue-600 transition">
                        完了した予約を見る
                    </a>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
