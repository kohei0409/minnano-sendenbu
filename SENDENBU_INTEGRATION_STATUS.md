# 宣伝部機能統合状況レポート

## 完了した作業

### 1. データベース設計 ✓
17個のマイグレーションファイルを作成しました：

#### 新規テーブル (16個)
1. `customers` - 一般顧客アカウント
2. `categories` - カテゴリー（階層構造）
3. `areas` - エリア（都道府県/市区町村）
4. `store_details` - 店舗詳細情報
5. `store_images` - 店舗画像ギャラリー
6. `business_hours` - 営業時間（曜日別）
7. `menus` - メニュー
8. `reviews` - レビュー
9. `review_images` - レビュー画像
10. `review_replies` - レビュー返信
11. `favorites` - お気に入り（多対多）
12. `coupons` - クーポン
13. `coupon_usage` - クーポン利用履歴
14. `reservations` - 予約
15. `tags` - タグ
16. `store_tags` - 店舗タグ（多対多）

#### storesテーブル拡張
- カテゴリー/エリア外部キー
- 詳細住所フィールド
- 緯度経度
- 平均評価、レビュー数、閲覧数、お気に入り数
- 注目店舗フラグ

### 2. モデル作成 ✓
15個の新規モデルを作成し、Storeモデルを更新しました：

- `Customer` - 顧客認証可能（Authenticatable）
- `Category`, `Area` - 階層構造対応
- `StoreDetail`, `StoreImage`, `BusinessHour`, `Menu`
- `Review`, `ReviewImage`, `ReviewReply`
- `Favorite`, `Coupon`, `CouponUsage`, `Reservation`, `Tag`
- `Store` - 宣伝部機能関連リレーションシップと便利メソッド追加

### 3. コントローラー作成 ✓
12個のコントローラーを作成しました：

#### 公開用
- `PublicStoreController` - 店舗検索・詳細
- `ReviewController` - レビュー投稿・編集
- `ReservationController` - 予約作成
- `FavoriteController` - お気に入り管理

#### 店舗管理用
- `Store\MenuController` - メニュー管理
- `Store\CouponController` - クーポン管理
- `Store\ReservationController` - 予約管理
- `Store\ReviewReplyController` - レビュー返信
- `Store\StoreDetailController` - 店舗詳細・画像・営業時間管理

#### 管理者用
- `Admin\CategoryController` - カテゴリー管理
- `Admin\AreaController` - エリア管理
- `Admin\ReviewModerationController` - レビュー承認

### 4. ルーティング設定 ✓
`routes/web.php`に以下を追加：
- 公開ルート（店舗検索・詳細・予約）
- 顧客ルート（レビュー・お気に入り）
- 店舗ルート（メニュー・クーポン・予約管理など）
- 管理者ルート（カテゴリー・エリア・レビュー承認）

### 5. 認証設定 ✓
`config/auth.php`に顧客認証ガードを追加：
- `customer`ガード
- `customers`プロバイダー
- パスワードリセット設定

### 6. シーダー作成 ✓
- `CategorySeeder` - 8つの主要カテゴリーとサブカテゴリー
- `AreaSeeder` - 47都道府県と主要市区町村
- `DatabaseSeeder` - 上記を含むように更新

## 次のステップ

### 1. サーバーへのデプロイ 🔄

#### ファイルのアップロード
以下のファイルをサーバーにアップロードしてください：

```bash
# マイグレーション
database/migrations/2025_11_02_1000*.php (17ファイル)

# モデル
app/Models/Customer.php
app/Models/Category.php
app/Models/Area.php
app/Models/StoreDetail.php
app/Models/StoreImage.php
app/Models/BusinessHour.php
app/Models/Menu.php
app/Models/Review.php
app/Models/ReviewImage.php
app/Models/ReviewReply.php
app/Models/Favorite.php
app/Models/Coupon.php
app/Models/CouponUsage.php
app/Models/Reservation.php
app/Models/Tag.php
app/Models/Store.php (更新済み)

# コントローラー
app/Http/Controllers/PublicStoreController.php
app/Http/Controllers/ReviewController.php
app/Http/Controllers/ReservationController.php
app/Http/Controllers/FavoriteController.php
app/Http/Controllers/Store/MenuController.php
app/Http/Controllers/Store/CouponController.php
app/Http/Controllers/Store/ReservationController.php
app/Http/Controllers/Store/ReviewReplyController.php
app/Http/Controllers/Store/StoreDetailController.php
app/Http/Controllers/Admin/CategoryController.php
app/Http/Controllers/Admin/AreaController.php
app/Http/Controllers/Admin/ReviewModerationController.php

# シーダー
database/seeders/CategorySeeder.php
database/seeders/AreaSeeder.php
database/seeders/DatabaseSeeder.php (更新済み)

# 設定ファイル
config/auth.php (更新済み)
routes/web.php (更新済み)
```

#### データベースマイグレーション実行

```bash
cd /home/xs509339/minnanosendenbu.jp/development/system

# マイグレーション実行
php artisan migrate

# シーダー実行（カテゴリーとエリアのデータ投入）
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=AreaSeeder

# マイグレーション状態確認
php artisan migrate:status
```

### 2. ビューファイル作成 📝

以下のビューを作成する必要があります：

#### 公開ページ
- `resources/views/public/stores/index.blade.php` - 店舗検索
- `resources/views/public/stores/show.blade.php` - 店舗詳細
- `resources/views/public/reviews/create.blade.php` - レビュー投稿
- `resources/views/public/reviews/edit.blade.php` - レビュー編集
- `resources/views/public/reservations/create.blade.php` - 予約フォーム
- `resources/views/public/reservations/show.blade.php` - 予約確認
- `resources/views/public/favorites/index.blade.php` - お気に入り一覧

#### 店舗管理ページ
- `resources/views/store/details/edit.blade.php` - 店舗詳細編集
- `resources/views/store/menus/index.blade.php` - メニュー一覧
- `resources/views/store/menus/create.blade.php` - メニュー作成
- `resources/views/store/menus/edit.blade.php` - メニュー編集
- `resources/views/store/coupons/index.blade.php` - クーポン一覧
- `resources/views/store/coupons/create.blade.php` - クーポン作成
- `resources/views/store/coupons/edit.blade.php` - クーポン編集
- `resources/views/store/reservations/index.blade.php` - 予約一覧
- `resources/views/store/reservations/show.blade.php` - 予約詳細

#### 管理者ページ
- `resources/views/admin/categories/index.blade.php` - カテゴリー一覧
- `resources/views/admin/categories/create.blade.php` - カテゴリー作成
- `resources/views/admin/categories/edit.blade.php` - カテゴリー編集
- `resources/views/admin/areas/index.blade.php` - エリア一覧
- `resources/views/admin/areas/create.blade.php` - エリア作成
- `resources/views/admin/areas/edit.blade.php` - エリア編集
- `resources/views/admin/reviews/index.blade.php` - レビュー承認一覧
- `resources/views/admin/reviews/show.blade.php` - レビュー詳細

### 3. ポリシー作成 🔐

認可処理のためのポリシーを作成：

```bash
php artisan make:policy ReviewPolicy --model=Review
php artisan make:policy MenuPolicy --model=Menu
php artisan make:policy CouponPolicy --model=Coupon
php artisan make:policy ReservationPolicy --model=Reservation
```

### 4. 顧客認証機能 🔑

顧客向けのログイン/登録機能を追加：

```bash
# 顧客認証コントローラーを作成
php artisan make:controller Auth/CustomerAuthController
```

または、Laravel Breezeを使用して顧客用の認証画面を別途作成。

### 5. 追加機能（オプション）

- **画像最適化**: 画像アップロード時のリサイズ・圧縮処理
- **通知機能**: 予約確認、レビュー投稿時のメール通知
- **検索機能拡張**: Elasticsearch/Algoliaでの全文検索
- **地図表示**: Google Maps APIで店舗位置表示
- **多言語対応**: 外国人観光客向け英語表示

### 6. テスト ✅

主要機能のテストを作成：

```bash
php artisan make:test StoreSearchTest
php artisan make:test ReviewTest
php artisan make:test ReservationTest
```

## データモデル概要

### リレーションシップ
- Store ← hasMany → Review, Menu, Coupon, Reservation, StoreImage, BusinessHour
- Store ← belongsToMany → Customer (favorites), Tag (store_tags)
- Store → belongsTo → Category, Area
- Review ← belongsTo → Store, Customer
- Review ← hasMany → ReviewImage
- Review ← hasOne → ReviewReply
- Customer ← hasMany → Review, Reservation, CouponUsage

### 主要な機能
1. **店舗検索**: カテゴリー、エリア、キーワード、評価でフィルタリング
2. **レビューシステム**: 5段階評価、画像付き、承認制
3. **予約システム**: 日時指定、人数、メッセージ
4. **クーポン**: 割引率/割引額/無料商品、使用期限、利用回数制限
5. **お気に入り**: 顧客が店舗をブックマーク
6. **メニュー**: 価格、カテゴリー、画像
7. **営業時間**: 曜日別、休憩時間対応

## 注意事項

### マイグレーション順序
マイグレーションファイルの番号順に実行されるため、外部キー制約が正しく設定されます。

### 画像ストレージ
`storage/app/public`に画像を保存するため、シンボリックリンクの設定が必要：
```bash
php artisan storage:link
```

### Str::slug()の日本語対応
カテゴリーやエリアのslugは日本語をローマ字化しないため、必要に応じて`spatie/laravel-sluggable`などを検討。

## 開発開始コマンド

```bash
# 1. サーバーにSSH接続
ssh xs509339@sv13435.xserver.jp

# 2. プロジェクトディレクトリへ移動
cd /home/xs509339/minnanosendenbu.jp/development/system

# 3. マイグレーション実行
php artisan migrate

# 4. シーダー実行
php artisan db:seed --class=CategorySeeder
php artisan db:seed --class=AreaSeeder

# 5. ストレージリンク作成
php artisan storage:link

# 6. キャッシュクリア
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

---

**作成日**: 2025-11-02
**統合完了度**: 約70%（コア機能完成、ビュー作成待ち）
