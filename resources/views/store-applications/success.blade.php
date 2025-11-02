<x-guest-layout>
    <div class="max-w-2xl mx-auto text-center">
        <div class="bg-green-50 border border-green-200 rounded-lg p-8">
            <div class="text-green-600 mb-4">
                <svg class="w-16 h-16 mx-auto" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-900 mb-4">申請を受け付けました</h2>
            <p class="text-gray-700 mb-6">
                店舗登録申請をありがとうございます。<br>
                管理者が申請内容を確認し、承認または却下の連絡をメールでお送りいたします。<br>
                通常、1〜3営業日以内に返信いたします。
            </p>
            <a href="{{ route('store-applications.create') }}" class="inline-flex items-center px-4 py-2 bg-gray-800 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-gray-700">
                別の申請を送信
            </a>
        </div>
    </div>
</x-guest-layout>
