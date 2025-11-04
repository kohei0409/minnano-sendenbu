# 多言語対応セットアップガイド

このドキュメントでは、「みんなの宣伝部」の多言語対応機能について説明します。

## 概要

本アプリケーションは、日本語と英語の2言語に対応しています。ユーザーは画面右上の言語切り替えボタンから、いつでも言語を変更できます。

## サポート言語

- **日本語 (ja)** - デフォルト言語
- **英語 (en)**

## 仕組み

### 1. 言語ファイル

翻訳は `lang/` ディレクトリ内の言語ファイルで管理されています。

```
lang/
├── ja/          # 日本語
│   ├── common.php
│   ├── store.php
│   ├── review.php
│   └── reservation.php
└── en/          # 英語
    ├── common.php
    ├── store.php
    ├── review.php
    └── reservation.php
```

#### ファイル構成

- **common.php** - ナビゲーション、ボタン、フォームラベル、ステータスなどの共通翻訳
- **store.php** - 店舗検索、店舗詳細、カテゴリー、エリアなど店舗関連の翻訳
- **review.php** - レビュー投稿、評価、コメントなどレビュー関連の翻訳
- **reservation.php** - 予約フォーム、予約確認、予約履歴など予約関連の翻訳

### 2. SetLocale ミドルウェア

`app/Http/Middleware/SetLocale.php` が言語設定を管理します。

言語の決定順序：
1. URLパラメータ `?lang=en` または `?lang=ja`
2. セッションに保存された言語設定
3. ブラウザの Accept-Language ヘッダー
4. デフォルト言語（日本語）

### 3. 言語切り替え

ユーザーは画面右上の言語切り替えボタンから言語を変更できます。

- **日本語** - 日本語に切り替え
- **English** - 英語に切り替え

選択された言語はセッションに保存され、サイト全体に適用されます。

## 使用方法

### ビューで翻訳を使用する

Bladeテンプレートで `__()` ヘルパー関数を使用します。

```php
<!-- 基本的な使い方 -->
<h1>{{ __('common.home') }}</h1>

<!-- パラメータを使用 -->
<p>{{ __('store.results_found', ['count' => $stores->total()]) }}</p>

<!-- @lang ディレクティブ -->
<button>@lang('common.search')</button>
```

### コントローラで翻訳を使用する

```php
use Illuminate\Support\Facades\App;

// 現在の言語を取得
$locale = App::getLocale(); // 'ja' または 'en'

// 翻訳を取得
$message = __('common.success');

// パラメータ付き
$message = __('store.results_found', ['count' => 10]);
```

### JavaScriptで翻訳を使用する

Bladeテンプレート内のJavaScriptコードでは、`{{ __() }}` を使用できます。

```javascript
<script>
    const message = '{{ __('store.loading_map') }}';
    console.log(message);
</script>
```

## 新しい言語の追加

### 1. 言語ファイルを作成

```bash
mkdir lang/fr  # フランス語の例
```

### 2. 翻訳ファイルをコピー

```bash
cp lang/ja/*.php lang/fr/
```

### 3. ファイルを翻訳

`lang/fr/` 内の各ファイルの値をフランス語に翻訳します。

### 4. SetLocale ミドルウェアを更新

`app/Http/Middleware/SetLocale.php` の `$supportedLocales` 配列に新しい言語を追加：

```php
protected array $supportedLocales = ['ja', 'en', 'fr'];
```

### 5. 言語切り替えボタンを追加

`resources/views/components/layouts/public.blade.php` に新しい言語のリンクを追加：

```html
<a href="{{ request()->fullUrlWithQuery(['lang' => 'fr']) }}"
   class="hover:text-orange-100 font-medium {{ app()->getLocale() == 'fr' ? 'underline' : '' }}">
    Français
</a>
```

## 翻訳の追加・編集

### 新しい翻訳キーを追加

1. 該当する言語ファイルを開く（例：`lang/ja/store.php`）
2. 配列に新しいキーと値を追加：

```php
return [
    // 既存の翻訳...

    'new_key' => '新しい翻訳',
];
```

3. 他の言語ファイルにも同じキーを追加

```php
// lang/en/store.php
return [
    // Existing translations...

    'new_key' => 'New Translation',
];
```

4. ビューで使用：

```php
{{ __('store.new_key') }}
```

### 既存の翻訳を編集

該当する言語ファイルを開いて、値を直接編集してください。

## パラメータ付き翻訳

翻訳テキストに動的な値を含める場合は、プレースホルダーを使用します。

### 翻訳ファイル

```php
// lang/ja/store.php
'results_found' => ':count件の店舗が見つかりました',

// lang/en/store.php
'results_found' => ':count stores found',
```

### ビューで使用

```php
{{ __('store.results_found', ['count' => $stores->total()]) }}
```

## ベストプラクティス

### 1. キーの命名規則

- 小文字とアンダースコアを使用
- ファイル名.キー名の形式で使用（例：`common.search`、`store.filter_category`）
- 明確で説明的な名前を使用

### 2. ファイルの分類

- **common.php** - アプリ全体で使用する共通の翻訳
- **[機能名].php** - 特定の機能に関連する翻訳

### 3. 翻訳の一貫性

- 同じ意味の文言には同じ翻訳キーを使用
- 全ての言語ファイルで同じキーを保持
- 定期的に翻訳の品質をレビュー

### 4. エスケープ

Bladeテンプレートで `{{ __() }}` を使用すると、HTMLエスケープが自動的に行われます。

```php
<!-- 自動エスケープ（推奨） -->
{{ __('common.name') }}

<!-- エスケープなし（HTMLを含む場合のみ） -->
{!! __('common.name') !!}
```

## トラブルシューティング

### 翻訳が表示されない

1. **キーが正しいか確認**
   ```php
   // 正しい
   {{ __('common.search') }}

   // 間違い（ファイル名が必要）
   {{ __('search') }}
   ```

2. **言語ファイルにキーが存在するか確認**
   - `lang/ja/common.php` に `'search' => '検索'` が存在するか

3. **キャッシュをクリア**
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan view:clear
   ```

### 言語が切り替わらない

1. セッションが正しく動作しているか確認
2. `.env` ファイルで `SESSION_DRIVER` が設定されているか確認
3. ブラウザのキャッシュをクリア

### デフォルト言語を変更

`app/Http/Middleware/SetLocale.php` の `$defaultLocale` を変更：

```php
protected string $defaultLocale = 'en'; // 英語をデフォルトに
```

または、`config/app.php` の `locale` を変更：

```php
'locale' => 'en',
```

## 参考資料

- [Laravel 公式ドキュメント - 多言語化](https://laravel.com/docs/10.x/localization)
- プロジェクトの言語ファイル：`lang/ja/` および `lang/en/`

---

## サンプルコード

### ビューでの使用例

```php
<x-layouts.public>
    <x-slot name="title">{{ __('store.search_title') }}</x-slot>

    <h1>{{ __('common.home') }}</h1>

    <p>{{ __('store.results_found', ['count' => 42]) }}</p>

    <button>{{ __('common.search') }}</button>
</x-layouts.public>
```

### コントローラでの使用例

```php
public function store(Request $request)
{
    // フラッシュメッセージで翻訳を使用
    return redirect()
        ->route('stores.index')
        ->with('success', __('common.saved_successfully'));
}
```

### バリデーションメッセージ

```php
$request->validate([
    'name' => 'required',
    'email' => 'required|email',
], [
    'name.required' => __('validation.name_required'),
    'email.required' => __('validation.email_required'),
]);
```

---

このガイドに従うことで、アプリケーションの多言語対応を効果的に管理・拡張できます。
