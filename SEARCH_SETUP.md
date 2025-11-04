# Laravel Scout 検索機能セットアップガイド

このドキュメントでは、Laravel Scoutを使った店舗検索機能のセットアップ手順を説明します。

## 目次

1. [Laravel Scoutのインストール](#1-laravel-scoutのインストール)
2. [設定ファイルのPublish](#2-設定ファイルのpublish)
3. [Collection Engineの設定](#3-collection-engineの設定)
4. [インデックスの作成](#4-インデックスの作成)
5. [使用方法](#5-使用方法)
6. [注意事項](#6-注意事項)
7. [トラブルシューティング](#7-トラブルシューティング)

---

## 1. Laravel Scoutのインストール

Composerを使用してLaravel Scoutをインストールします。

```bash
composer require laravel/scout
```

インストール完了後、以下のメッセージが表示されることを確認してください：

```
Package manifest generated successfully.
```

---

## 2. 設定ファイルのPublish

Laravel Scoutの設定ファイルを公開します。

```bash
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

これにより、`config/scout.php` ファイルが作成されます。

---

## 3. Collection Engineの設定

開発環境やデータベースサイズが小さい場合は、Collection Engineを使用することを推奨します。

### `.env` ファイルに以下を追加

```env
SCOUT_DRIVER=collection
```

### Collection Engineとは？

- **メリット：**
  - 外部サービス不要（追加のインフラ不要）
  - セットアップが簡単
  - 小規模なデータセットに最適
  - 開発環境に適している

- **デメリット：**
  - 大規模なデータセットには不向き
  - 本番環境では性能が劣る可能性あり

### 本番環境での推奨ドライバー

本番環境や大規模なデータセットの場合は、以下のいずれかのドライバーを検討してください：

- **Algolia** (`SCOUT_DRIVER=algolia`)
  - 高性能なクラウド検索サービス
  - 日本語全文検索に対応
  - 有料プランあり（無料枠あり）

- **Meilisearch** (`SCOUT_DRIVER=meilisearch`)
  - オープンソースの検索エンジン
  - 日本語対応
  - セルフホスティング可能

- **Database** (`SCOUT_DRIVER=database`)
  - Laravel 9.2以降で利用可能
  - MySQLやPostgreSQLの全文検索機能を使用
  - 外部サービス不要

---

## 4. インデックスの作成

既存のStoreデータをScoutのインデックスに登録します。

```bash
php artisan scout:import "App\Models\Store"
```

### 実行結果例

```
Imported [App\Models\Store] models up to ID: 100
Imported [App\Models\Store] models up to ID: 200
All [App\Models\Store] records have been imported.
```

### インデックスの再構築

データに変更があった場合や、検索対象フィールドを変更した場合は、以下のコマンドでインデックスを再構築します。

```bash
# インデックスをクリア
php artisan scout:flush "App\Models\Store"

# インデックスを再作成
php artisan scout:import "App\Models\Store"
```

---

## 5. 使用方法

### 検索対象フィールド

現在、以下のフィールドが検索対象となっています（`Store::toSearchableArray()` で定義）：

- `store_name` - 店舗名
- `industry` - 業種
- `street_address` - 住所（番地）
- `city` - 市区町村
- `prefecture` - 都道府県
- `description` - 説明（StoreDetailから）
- `catchphrase` - キャッチフレーズ（StoreDetailから）

### コントローラーでの使用例

```php
// キーワード検索
$stores = Store::search('東京 カフェ')->get();

// ステータスフィルター付き検索
$stores = Store::search('レストラン')
    ->where('status', 'active')
    ->get();

// ページネーション
$stores = Store::search('ラーメン')
    ->where('status', 'active')
    ->paginate(20);
```

### 実装済みの機能

`PublicStoreController::index()` メソッドでは、以下のように実装されています：

1. **キーワードが入力された場合：**
   - Scout検索を使用
   - カテゴリー、エリア、評価のフィルターを適用
   - ソート機能にも対応

2. **キーワードが入力されていない場合：**
   - 従来通りのクエリビルダーを使用
   - フィルターとソート機能を適用

---

## 6. 注意事項

### データの自動同期

Scoutは以下の操作で自動的にインデックスを更新します：

- モデルの作成時 (`create()`, `save()`)
- モデルの更新時 (`update()`, `save()`)
- モデルの削除時 (`delete()`, `forceDelete()`)

### 同期の無効化

一括インポートなど、一時的に同期を無効にしたい場合：

```php
use Laravel\Scout\Searchable;

Store::withoutSyncingToSearch(function () {
    // この中での操作はインデックスに同期されません
    Store::create([...]);
});

// 後でまとめてインデックス化
php artisan scout:import "App\Models\Store"
```

### ソフトデリート

ソフトデリートされたモデルは自動的にインデックスから削除されます。

---

## 7. トラブルシューティング

### 検索結果が返されない

1. **インデックスが作成されているか確認：**

```bash
php artisan scout:import "App\Models\Store"
```

2. **Storeモデルに `Searchable` トレイトがあるか確認：**

```php
use Laravel\Scout\Searchable;

class Store extends Model
{
    use HasFactory, SoftDeletes, Searchable;
}
```

3. **`.env` ファイルでドライバーが設定されているか確認：**

```env
SCOUT_DRIVER=collection
```

### 検索が遅い

Collection Engineは小規模なデータセット向けです。データが増えてきたら、以下を検討してください：

- **Database Driver** に切り替え
- **Meilisearch** や **Algolia** などの専用検索エンジンを使用

### StoreDetailのデータが検索できない

`toSearchableArray()` メソッドで `storeDetail` リレーションが読み込まれているか確認してください。インデックスを再構築する必要がある場合があります：

```bash
php artisan scout:flush "App\Models\Store"
php artisan scout:import "App\Models\Store"
```

### Collection Engineでの制限事項

Collection Engineは以下の機能に制限があります：

- 日本語の形態素解析は行われません（部分一致検索）
- 大規模データでは性能が劣ります
- 本番環境では推奨されません

---

## その他のコマンド

### キューを使用したインポート

大量のデータをインポートする場合は、キューを使用することを推奨します：

```bash
# キューワーカーを起動
php artisan queue:work

# 別のターミナルでインポート（キュー使用）
php artisan scout:import "App\Models\Store" --chunk=100
```

### 特定の条件でインポート

```php
// 例：アクティブな店舗のみをインデックス化
Store::where('status', 'active')->searchable();
```

---

## 参考リンク

- [Laravel Scout 公式ドキュメント](https://laravel.com/docs/10.x/scout)
- [Collection Engine](https://laravel.com/docs/10.x/scout#collection-engine)
- [Meilisearch](https://www.meilisearch.com/)
- [Algolia](https://www.algolia.com/)

---

## 更新履歴

- 2025-11-04: 初版作成
