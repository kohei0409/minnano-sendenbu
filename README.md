# みんなの宣伝部（Minna no Sendenbu）

地域店舗情報プラットフォーム - Laravel 10

[![Laravel](https://img.shields.io/badge/Laravel-10.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.2-blue.svg)](https://php.net)
[![License](https://img.shields.io/badge/license-Proprietary-green.svg)](LICENSE)

## 📖 概要

**みんなの宣伝部**は、地域の店舗と顧客をつなぐ総合情報プラットフォームです。店舗検索、レビュー、予約、クーポンなど、包括的な機能を提供します。

### 主な機能

- 🔍 **店舗検索** - キーワード、カテゴリー、エリア、評価でフィルタリング
- ⭐ **レビューシステム** - 5段階評価、画像投稿、店舗返信機能
- 📅 **予約システム** - オンライン予約、予約管理
- 💰 **クーポン機能** - 割引クーポンの発行・管理
- ❤️ **お気に入り** - 気になる店舗をブックマーク
- 🗺️ **地図表示** - Google Maps APIによる店舗位置表示
- 🌍 **多言語対応** - 日本語・英語切り替え
- 📧 **メール通知** - 予約確認、レビュー承認などの自動通知
- 🖼️ **画像最適化** - 自動リサイズ・圧縮

## 🚀 技術スタック

### バックエンド
- **Laravel 10.x** - PHPフレームワーク
- **PHP 8.2** - プログラミング言語
- **MySQL** - データベース
- **Laravel Breeze** - 認証システム
- **Spatie Laravel Permission** - 権限管理

### フロントエンド
- **Tailwind CSS** - CSSフレームワーク
- **Alpine.js** - JavaScriptフレームワーク
- **Blade** - テンプレートエンジン

### 拡張機能
- **Laravel Scout** - 全文検索（オプション）
- **Intervention Image** - 画像処理
- **Google Maps API** - 地図表示
- **Chart.js** - グラフ表示

## 📋 システム要件

- PHP >= 8.2
- MySQL >= 8.0
- Composer
- Node.js & NPM
- GD Library または Imagick（画像処理用）

## 🛠️ インストール

### 1. リポジトリのクローン

```bash
git clone [repository-url]
cd web
```

### 2. 依存関係のインストール

```bash
# Composer依存関係
composer install

# NPM依存関係
npm install
npm run build
```

### 3. 環境設定

```bash
# .envファイルの作成
cp .env.example .env

# アプリケーションキーの生成
php artisan key:generate
```

### 4. データベース設定

`.env`ファイルでデータベース接続情報を設定：

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=minna_sendenbu
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

### 5. マイグレーションとシーダー

```bash
# データベースマイグレーション
php artisan migrate

# 初期データの投入
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=AreaSeeder
php artisan db:seed --class=StoreUserSeeder  # テストデータ（オプション）
```

### 6. ストレージリンク

```bash
php artisan storage:link
```

### 7. 開発サーバーの起動

```bash
php artisan serve
```

アプリケーションは http://localhost:8000 でアクセスできます。

## 🔧 オプション機能の設定

### Laravel Scout（全文検索）

```bash
composer require laravel/scout
php artisan vendor:publish --provider="Laravel\Scout\ScoutServiceProvider"
```

`.env`に追加：
```env
SCOUT_DRIVER=collection
```

詳細は[SEARCH_SETUP.md](SEARCH_SETUP.md)を参照。

### Intervention Image（画像最適化）

```bash
composer require intervention/image
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"
```

詳細は[IMAGE_OPTIMIZATION_SETUP.md](IMAGE_OPTIMIZATION_SETUP.md)を参照。

### Google Maps API（地図表示）

`.env`に追加：
```env
GOOGLE_MAPS_API_KEY=your_api_key_here
```

詳細は[GOOGLE_MAPS_SETUP.md](GOOGLE_MAPS_SETUP.md)を参照。

### メール設定

`.env`に追加：
```env
MAIL_MAILER=smtp
MAIL_HOST=your-smtp-host
MAIL_PORT=587
MAIL_USERNAME=your-username
MAIL_PASSWORD=your-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=support@minna-sendenbu.jp
MAIL_FROM_NAME="みんなの宣伝部"
```

## 🧪 テスト

### テストの実行

```bash
# 全テスト実行
php artisan test

# 特定のテスト実行
php artisan test --filter=ReviewTest

# カバレッジレポート生成
php artisan test --coverage
```

詳細は[TESTING_GUIDE.md](TESTING_GUIDE.md)を参照。

## 📚 ドキュメント

- [統合状況レポート](SENDENBU_INTEGRATION_STATUS.md) - 全機能の実装状況
- [画像最適化セットアップ](IMAGE_OPTIMIZATION_SETUP.md)
- [検索機能セットアップ](SEARCH_SETUP.md)
- [Google Mapsセットアップ](GOOGLE_MAPS_SETUP.md)
- [多言語対応セットアップ](MULTILINGUAL_SETUP.md)
- [テストガイド](TESTING_GUIDE.md)

## 👥 ユーザーロール

### 管理者（Admin）
- 全機能へのアクセス
- 店舗申請の承認・却下
- レビューの承認・却下
- カテゴリー・エリアの管理
- ユーザー管理

### 店舗オーナー（Store Owner）
- 店舗情報の管理
- メニュー・クーポン・営業時間の設定
- 予約の管理
- レビューへの返信
- スタッフの管理

### 店舗スタッフ（Store Staff）
- 予約の管理
- レビューへの返信
- 店舗情報の閲覧

### 顧客（Customer）
- 店舗検索・閲覧
- レビュー投稿
- 予約作成
- お気に入り登録
- マイページ管理

## 🗂️ プロジェクト構造

```
web/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Admin/          # 管理者用コントローラー
│   │   │   ├── Store/          # 店舗管理用コントローラー
│   │   │   ├── Customer/       # 顧客用コントローラー
│   │   │   └── Auth/           # 認証コントローラー
│   │   ├── Middleware/
│   │   └── Requests/
│   ├── Models/                  # Eloquentモデル
│   ├── Mail/                    # Mailableクラス
│   ├── Services/                # サービスクラス
│   └── Policies/                # 認可ポリシー
├── database/
│   ├── migrations/              # データベースマイグレーション
│   ├── seeders/                 # シーダー
│   └── factories/               # モデルファクトリー
├── resources/
│   ├── views/
│   │   ├── public/             # 公開ページ
│   │   ├── customer/           # 顧客マイページ
│   │   ├── store/              # 店舗管理画面
│   │   ├── admin/              # 管理者画面
│   │   └── emails/             # メールテンプレート
│   └── lang/                    # 言語ファイル
├── routes/
│   └── web.php                  # ルート定義
└── tests/
    └── Feature/                 # Featureテスト
```

## 📊 データベース構造

- **17テーブル** - users, customers, stores, reviews, reservations, favorites など
- **完全なリレーションシップ** - belongsTo, hasMany, belongsToMany
- **ソフトデリート対応** - 重要なデータは論理削除

詳細は[SENDENBU_INTEGRATION_STATUS.md](SENDENBU_INTEGRATION_STATUS.md)を参照。

## 🌐 多言語対応

現在対応している言語：
- 🇯🇵 日本語（デフォルト）
- 🇬🇧 English

詳細は[MULTILINGUAL_SETUP.md](MULTILINGUAL_SETUP.md)を参照。

## 🔐 セキュリティ

- CSRF保護
- XSS対策
- SQLインジェクション対策
- パスワードハッシュ化（bcrypt）
- 認証・認可システム（Laravel標準）
- ポリシーベースのアクセス制御

## 📈 パフォーマンス最適化

- Eagerローディング
- クエリ最適化
- 画像の自動圧縮・リサイズ
- キャッシュ機能
- ページネーション

## 🚢 本番環境へのデプロイ

詳細な手順は[SENDENBU_INTEGRATION_STATUS.md](SENDENBU_INTEGRATION_STATUS.md)の「デプロイ手順」セクションを参照してください。

### 主要ステップ

1. 依存関係のインストール
2. 環境変数の設定
3. データベースマイグレーション
4. ストレージリンクの作成
5. キャッシュの最適化
6. 権限設定

## 🤝 コントリビューション

このプロジェクトは現在プライベートです。

## 📄 ライセンス

Proprietary - アドトラスト株式会社

## 📞 サポート

- **プロジェクト管理**: アドトラスト株式会社
- **技術サポート**: Claude Code

## 📝 更新履歴

### Version 1.0.0 (2025-11-04)
- 初回リリース
- 全コア機能実装完了
- 全拡張機能実装完了
- テスト作成完了

---

**🎉 みんなの宣伝部 - 地域をつなぐ、店舗情報プラットフォーム**
