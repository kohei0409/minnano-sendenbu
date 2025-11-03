<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            メニュー編集
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <form method="POST" action="{{ route('store.menus.update', $menu) }}" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        <!-- Name -->
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">メニュー名 <span class="text-red-500">*</span></label>
                            <input id="name" type="text" name="name" value="{{ old('name', $menu->name) }}" required autofocus
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('name') border-red-500 @enderror">
                            @error('name')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Description -->
                        <div class="mt-4">
                            <label for="description" class="block text-sm font-medium text-gray-700">説明</label>
                            <textarea id="description" name="description" rows="3"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('description') border-red-500 @enderror">{{ old('description', $menu->description) }}</textarea>
                            @error('description')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Price -->
                        <div class="mt-4">
                            <label for="price" class="block text-sm font-medium text-gray-700">価格 <span class="text-red-500">*</span></label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-gray-500 sm:text-sm">¥</span>
                                </div>
                                <input id="price" type="number" name="price" value="{{ old('price', $menu->price) }}" required min="0" step="1"
                                    class="w-full pl-7 pr-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('price') border-red-500 @enderror">
                            </div>
                            @error('price')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Category -->
                        <div class="mt-4">
                            <label for="category" class="block text-sm font-medium text-gray-700">カテゴリ</label>
                            <input id="category" type="text" name="category" value="{{ old('category', $menu->category) }}"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('category') border-red-500 @enderror"
                                placeholder="例: 前菜、メイン、デザート">
                            @error('category')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Current Image -->
                        @if($menu->image_path)
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">現在の画像</label>
                                <img src="{{ asset('storage/' . $menu->image_path) }}" alt="{{ $menu->name }}" class="mt-2 h-32 w-32 object-cover rounded">
                            </div>
                        @endif

                        <!-- Image -->
                        <div class="mt-4">
                            <label for="image" class="block text-sm font-medium text-gray-700">画像 @if($menu->image_path)<span class="text-gray-500">(変更する場合のみ)</span>@endif</label>
                            <input id="image" type="file" name="image" accept="image/*"
                                class="mt-1 w-full px-4 py-2 border border-gray-300 rounded-md focus:ring-orange-500 focus:border-orange-500 @error('image') border-red-500 @enderror">
                            <p class="mt-1 text-xs text-gray-500">最大5MBまで。JPG、PNG、GIF形式をサポート</p>
                            @error('image')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Is Available -->
                        <div class="mt-4">
                            <label class="flex items-center">
                                <input type="checkbox" name="is_available" value="1" {{ old('is_available', $menu->is_available) ? 'checked' : '' }}
                                    class="rounded border-gray-300 text-orange-600 shadow-sm focus:border-orange-300 focus:ring focus:ring-orange-200 focus:ring-opacity-50">
                                <span class="ml-2 text-sm text-gray-700">提供可能</span>
                            </label>
                            @error('is_available')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="flex items-center justify-end mt-6 gap-4">
                            <a href="{{ route('store.menus.index') }}" class="px-4 py-2 border border-gray-300 text-gray-700 rounded-md hover:bg-gray-50">
                                キャンセル
                            </a>
                            <button type="submit" class="px-4 py-2 bg-orange-600 text-white rounded-md hover:bg-orange-700">
                                更新
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
