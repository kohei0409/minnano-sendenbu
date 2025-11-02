<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('管理者ダッシュボード') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-semibold mb-4">管理メニュー</h3>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <a href="{{ route('admin.applications.index') }}" class="block p-6 bg-blue-50 hover:bg-blue-100 rounded-lg border border-blue-200">
                            <h4 class="font-bold text-lg mb-2">店舗申請管理</h4>
                            <p class="text-gray-700">新規店舗の申請を承認・却下します</p>
                        </a>

                        <a href="{{ route('admin.users.index') }}" class="block p-6 bg-green-50 hover:bg-green-100 rounded-lg border border-green-200">
                            <h4 class="font-bold text-lg mb-2">ユーザー管理</h4>
                            <p class="text-gray-700">ユーザーのレベルやステータスを管理します</p>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
