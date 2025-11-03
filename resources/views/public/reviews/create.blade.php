<x-layouts.public>
    <x-slot name="title">レビューを投稿 - {{ $store->store_name }}</x-slot>

    <div class="min-h-screen bg-gray-50 py-8">
        <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- パンくずリスト -->
            <nav class="mb-6 text-sm">
                <ol class="flex items-center space-x-2 text-gray-500">
                    <li><a href="{{ route('home') }}" class="hover:text-orange-600">ホーム</a></li>
                    <li>/</li>
                    <li><a href="{{ route('stores.index') }}" class="hover:text-orange-600">店舗一覧</a></li>
                    <li>/</li>
                    <li><a href="{{ route('stores.show', $store) }}" class="hover:text-orange-600">{{ $store->store_name }}</a></li>
                    <li>/</li>
                    <li class="text-gray-900">レビューを投稿</li>
                </ol>
            </nav>

            <!-- ヘッダー -->
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="flex items-center space-x-4">
                    @php
                        $mainImage = $store->images()->where('is_main', true)->first() ?? $store->images()->first();
                    @endphp
                    @if($mainImage)
                        <img src="{{ asset('storage/' . $mainImage->image_path) }}"
                             alt="{{ $store->store_name }}"
                             class="w-20 h-20 object-cover rounded-lg">
                    @else
                        <div class="w-20 h-20 bg-gray-200 rounded-lg flex items-center justify-center">
                            <svg class="w-10 h-10 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                        </div>
                    @endif
                    <div>
                        <h1 class="text-2xl font-bold text-gray-900">{{ $store->store_name }}</h1>
                        <p class="text-sm text-gray-600">{{ $store->category->name ?? '' }} / {{ $store->prefecture }}{{ $store->city }}</p>
                    </div>
                </div>
            </div>

            <!-- レビュー投稿フォーム -->
            <div class="bg-white rounded-lg shadow-md p-6">
                <h2 class="text-xl font-bold text-gray-900 mb-6">レビューを投稿する</h2>

                <form action="{{ route('customer.reviews.store', $store) }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <!-- 評価 -->
                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            総合評価 <span class="text-red-600">*</span>
                        </label>
                        <div class="flex items-center space-x-2">
                            <div class="flex space-x-1" id="rating-stars">
                                @for($i = 1; $i <= 5; $i++)
                                    <button type="button"
                                            class="rating-star text-3xl text-gray-300 hover:text-yellow-400 focus:outline-none transition"
                                            data-rating="{{ $i }}">
                                        ★
                                    </button>
                                @endfor
                            </div>
                            <span id="rating-text" class="text-sm text-gray-600 ml-3"></span>
                        </div>
                        <input type="hidden" name="rating" id="rating-input" value="{{ old('rating', 0) }}">
                        @error('rating')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- タイトル -->
                    <div class="mb-6">
                        <label for="title" class="block text-sm font-medium text-gray-700 mb-2">
                            タイトル <span class="text-red-600">*</span>
                        </label>
                        <input type="text"
                               name="title"
                               id="title"
                               value="{{ old('title') }}"
                               placeholder="例：美味しい料理とサービスが最高でした！"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('title') border-red-500 @enderror"
                               required>
                        @error('title')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- レビュー本文 -->
                    <div class="mb-6">
                        <label for="content" class="block text-sm font-medium text-gray-700 mb-2">
                            レビュー <span class="text-red-600">*</span>
                        </label>
                        <textarea name="content"
                                  id="content"
                                  rows="8"
                                  placeholder="お店の雰囲気、料理の味、サービスなどについて詳しく教えてください..."
                                  class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('content') border-red-500 @enderror"
                                  required>{{ old('content') }}</textarea>
                        <p class="mt-1 text-xs text-gray-500">具体的に書いていただくと、他のユーザーの参考になります</p>
                        @error('content')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 訪問日 -->
                    <div class="mb-6">
                        <label for="visit_date" class="block text-sm font-medium text-gray-700 mb-2">
                            訪問日
                        </label>
                        <input type="date"
                               name="visit_date"
                               id="visit_date"
                               value="{{ old('visit_date') }}"
                               max="{{ date('Y-m-d') }}"
                               class="w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('visit_date') border-red-500 @enderror">
                        @error('visit_date')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 画像アップロード -->
                    <div class="mb-6">
                        <label for="images" class="block text-sm font-medium text-gray-700 mb-2">
                            写真（最大5枚まで）
                        </label>
                        <div class="border-2 border-dashed border-gray-300 rounded-lg p-6 text-center hover:border-orange-500 transition">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            <div class="mt-2">
                                <label for="images" class="cursor-pointer text-orange-600 hover:text-orange-700 font-medium">
                                    ファイルを選択
                                </label>
                                <input type="file"
                                       name="images[]"
                                       id="images"
                                       multiple
                                       accept="image/*"
                                       class="hidden"
                                       onchange="previewImages(event)">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">JPG, PNG, GIF（1枚あたり最大5MB）</p>
                        </div>
                        <div id="preview-container" class="mt-4 grid grid-cols-2 sm:grid-cols-3 gap-4"></div>
                        @error('images.*')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- 注意事項 -->
                    <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
                        <h3 class="font-medium text-orange-900 mb-2">レビュー投稿ガイドライン</h3>
                        <ul class="text-sm text-orange-800 space-y-1">
                            <li>• 実際に訪問した店舗のみレビューしてください</li>
                            <li>• 個人を特定できる情報や誹謗中傷は禁止です</li>
                            <li>• レビューは承認後に公開されます</li>
                        </ul>
                    </div>

                    <!-- ボタン -->
                    <div class="flex items-center justify-between space-x-4">
                        <a href="{{ route('stores.show', $store) }}"
                           class="px-6 py-3 border border-gray-300 rounded-md text-gray-700 hover:bg-gray-50 transition">
                            キャンセル
                        </a>
                        <button type="submit"
                                class="px-8 py-3 bg-gradient-to-r from-orange-500 to-orange-600 text-white font-bold rounded-md hover:from-orange-600 hover:to-orange-700 transition shadow-lg">
                            レビューを投稿する
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // 星評価の処理
        const stars = document.querySelectorAll('.rating-star');
        const ratingInput = document.getElementById('rating-input');
        const ratingText = document.getElementById('rating-text');

        const ratingLabels = {
            1: '悪い',
            2: 'イマイチ',
            3: '普通',
            4: '良い',
            5: '最高！'
        };

        // 初期値の設定
        const initialRating = parseInt(ratingInput.value) || 0;
        updateStars(initialRating);

        stars.forEach(star => {
            star.addEventListener('click', function() {
                const rating = parseInt(this.dataset.rating);
                ratingInput.value = rating;
                updateStars(rating);
            });

            star.addEventListener('mouseenter', function() {
                const rating = parseInt(this.dataset.rating);
                updateStars(rating);
            });
        });

        document.getElementById('rating-stars').addEventListener('mouseleave', function() {
            const currentRating = parseInt(ratingInput.value) || 0;
            updateStars(currentRating);
        });

        function updateStars(rating) {
            stars.forEach((star, index) => {
                if (index < rating) {
                    star.classList.remove('text-gray-300');
                    star.classList.add('text-yellow-400');
                } else {
                    star.classList.remove('text-yellow-400');
                    star.classList.add('text-gray-300');
                }
            });

            if (rating > 0) {
                ratingText.textContent = ratingLabels[rating];
            } else {
                ratingText.textContent = '';
            }
        }

        // 画像プレビュー
        function previewImages(event) {
            const files = event.target.files;
            const previewContainer = document.getElementById('preview-container');
            previewContainer.innerHTML = '';

            if (files.length > 5) {
                alert('画像は最大5枚までアップロードできます');
                event.target.value = '';
                return;
            }

            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative';
                    div.innerHTML = `
                        <img src="${e.target.result}" class="w-full h-32 object-cover rounded-lg">
                    `;
                    previewContainer.appendChild(div);
                };
                reader.readAsDataURL(file);
            });
        }
    </script>
</x-layouts.public>
