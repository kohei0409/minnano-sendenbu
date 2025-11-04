# Testing Guide

このガイドでは、「みんなの宣伝部」プロジェクトにおけるテストの実行方法と、テストデータの生成方法について説明します。

## 目次

1. [テスト環境のセットアップ](#テスト環境のセットアップ)
2. [テストの実行](#テストの実行)
3. [テストデータベースの設定](#テストデータベースの設定)
4. [ファクトリーの使用方法](#ファクトリーの使用方法)
5. [Featureテストの概要](#featureテストの概要)
6. [カバレッジレポートの生成](#カバレッジレポートの生成)
7. [テスト作成のベストプラクティス](#テスト作成のベストプラクティス)

## テスト環境のセットアップ

### 1. 必要な依存関係のインストール

```bash
composer install
```

### 2. テスト用環境変数の設定

`.env.testing` ファイルを作成し、テスト用の設定を行います：

```env
APP_ENV=testing
APP_DEBUG=true
APP_KEY=base64:YOUR_APP_KEY_HERE

DB_CONNECTION=sqlite
DB_DATABASE=:memory:

# または、専用のテストデータベースを使用する場合
# DB_CONNECTION=mysql
# DB_HOST=127.0.0.1
# DB_PORT=3306
# DB_DATABASE=sendenbu_test
# DB_USERNAME=root
# DB_PASSWORD=

MAIL_MAILER=log
QUEUE_CONNECTION=sync
SESSION_DRIVER=array
CACHE_DRIVER=array

# Scout設定（テスト環境では無効化推奨）
SCOUT_DRIVER=null
```

### 3. アプリケーションキーの生成

```bash
php artisan key:generate --env=testing
```

## テストの実行

### すべてのテストを実行

```bash
php artisan test
```

または

```bash
./vendor/bin/phpunit
```

### 特定のテストファイルを実行

```bash
php artisan test tests/Feature/StoreSearchTest.php
```

### 特定のテストメソッドを実行

```bash
php artisan test --filter test_customer_can_create_reservation
```

### テストグループを実行

```bash
php artisan test --group=feature
```

### 並列テストの実行（高速化）

```bash
php artisan test --parallel
```

## テストデータベースの設定

### SQLiteメモリデータベースを使用（推奨）

`.env.testing` で以下を設定：

```env
DB_CONNECTION=sqlite
DB_DATABASE=:memory:
```

この設定により、各テスト実行時にメモリ上に新しいデータベースが作成され、テスト終了後に自動的に破棄されます。

### MySQLテストデータベースを使用

専用のテストデータベースを作成する場合：

```bash
mysql -u root -p
CREATE DATABASE sendenbu_test;
```

`.env.testing` で設定：

```env
DB_CONNECTION=mysql
DB_DATABASE=sendenbu_test
```

## ファクトリーの使用方法

### ファクトリーの基本

ファクトリーはテストデータを簡単に生成するためのクラスです。プロジェクトには以下のファクトリーが用意されています：

- `CustomerFactory` - 顧客データの生成
- `StoreFactory` - 店舗データの生成
- `CategoryFactory` - カテゴリーデータの生成
- `AreaFactory` - エリアデータの生成
- `ReviewFactory` - レビューデータの生成
- `ReservationFactory` - 予約データの生成
- `FavoriteFactory` - お気に入りデータの生成

### ファクトリーの使用例

#### 1. 基本的な使用方法

```php
use App\Models\Customer;
use App\Models\Store;

// 1件のデータを生成
$customer = Customer::factory()->create();

// 複数のデータを生成
$customers = Customer::factory()->count(5)->create();
```

#### 2. 属性を上書きして生成

```php
$customer = Customer::factory()->create([
    'email' => 'test@example.com',
    'name' => 'Test User',
]);
```

#### 3. ステート（状態）を使用

```php
// 非アクティブな顧客を生成
$inactiveCustomer = Customer::factory()->inactive()->create();

// 承認待ちの店舗を生成
$pendingStore = Store::factory()->pending()->create();

// 公開済みのレビューを生成
$publishedReview = Review::factory()->published()->create();
```

#### 4. リレーションを含むデータ生成

```php
// 店舗とそれに関連するデータを生成
$category = Category::factory()->create();
$area = Area::factory()->create();

$store = Store::factory()->create([
    'category_id' => $category->id,
    'area_id' => $area->id,
]);

// レビューを店舗と顧客に紐付けて生成
$customer = Customer::factory()->create();
$review = Review::factory()->create([
    'store_id' => $store->id,
    'customer_id' => $customer->id,
]);
```

#### 5. makeメソッド（データベースに保存しない）

```php
// インスタンスのみ生成（DB保存なし）
$customer = Customer::factory()->make();

// バリデーションテストなどに便利
$customer = Customer::factory()->make([
    'email' => 'invalid-email',
]);
```

## Featureテストの概要

### 1. StoreSearchTest.php

店舗検索機能のテスト

```php
// テスト例
test_can_search_stores_by_keyword()
test_can_filter_stores_by_category()
test_can_filter_stores_by_area()
test_can_filter_stores_by_rating()
test_can_sort_stores_by_rating()
```

### 2. ReviewTest.php

レビュー機能のテスト

```php
// テスト例
test_authenticated_customer_can_create_review()
test_customer_can_edit_own_review()
test_customer_can_delete_own_review()
test_admin_can_approve_review()
test_admin_can_reject_review()
```

### 3. ReservationTest.php

予約機能のテスト

```php
// テスト例
test_guest_can_create_reservation()
test_authenticated_customer_can_create_reservation()
test_store_owner_can_confirm_reservation()
test_store_owner_can_cancel_reservation()
```

### 4. FavoriteTest.php

お気に入り機能のテスト

```php
// テスト例
test_authenticated_customer_can_add_favorite()
test_authenticated_customer_can_remove_favorite()
test_customer_can_view_favorites_list()
test_unauthenticated_user_cannot_add_favorite()
```

### 5. CustomerAuthTest.php

顧客認証機能のテスト

```php
// テスト例
test_customer_can_register()
test_customer_can_login_with_correct_credentials()
test_customer_can_logout()
test_customer_can_reset_password()
```

### 6. MyPageTest.php

マイページ機能のテスト

```php
// テスト例
test_customer_can_view_mypage_dashboard()
test_customer_can_update_profile()
test_customer_can_change_password()
test_customer_can_view_their_reservations()
```

## カバレッジレポートの生成

### 前提条件

Xdebugまたはpcovのインストールが必要です。

```bash
# Xdebugの確認
php -v | grep Xdebug

# pcovのインストール（推奨：高速）
pecl install pcov
```

### カバレッジレポートの生成

```bash
# HTML形式のレポート生成
php artisan test --coverage --coverage-html coverage-report

# 最小カバレッジを指定
php artisan test --coverage --min=80
```

生成されたレポートは `coverage-report/index.html` で確認できます。

### シンプルなカバレッジ表示

```bash
# コンソールにカバレッジを表示
php artisan test --coverage
```

## テスト作成のベストプラクティス

### 1. RefreshDatabaseトレイトの使用

各テストクラスで必ず `RefreshDatabase` トレイトを使用してください：

```php
use Illuminate\Foundation\Testing\RefreshDatabase;

class MyTest extends TestCase
{
    use RefreshDatabase;

    // テストメソッド
}
```

### 2. setUp メソッドで共通の準備処理

```php
protected function setUp(): void
{
    parent::setUp();

    // すべてのテストで使用する共通データ
    $this->customer = Customer::factory()->create();
}
```

### 3. 明確なテスト名

テスト名は何をテストしているか明確にします：

```php
// Good
public function test_customer_can_create_reservation(): void

// Bad
public function test_reservation(): void
```

### 4. 1テスト1アサーション（推奨）

可能な限り、1つのテストで1つの機能をテストします：

```php
// Good
public function test_customer_can_login(): void
{
    $customer = Customer::factory()->create();

    $response = $this->post(route('customer.login'), [
        'email' => $customer->email,
        'password' => 'password',
    ]);

    $this->assertAuthenticatedAs($customer, 'customer');
}
```

### 5. Arrange-Act-Assert パターン

```php
public function test_example(): void
{
    // Arrange: テストデータの準備
    $customer = Customer::factory()->create();

    // Act: テスト対象の処理を実行
    $response = $this->actingAs($customer, 'customer')
        ->get(route('customer.mypage.index'));

    // Assert: 結果を検証
    $response->assertStatus(200);
}
```

### 6. Fakeの使用

外部サービスやメール送信などはFakeを使用します：

```php
use Illuminate\Support\Facades\Mail;

public function test_sends_email(): void
{
    Mail::fake();

    // メール送信処理

    Mail::assertSent(WelcomeEmail::class);
}
```

### 7. データベースアサーション

```php
// レコードが存在することを確認
$this->assertDatabaseHas('customers', [
    'email' => 'test@example.com',
]);

// レコードが存在しないことを確認
$this->assertDatabaseMissing('customers', [
    'email' => 'deleted@example.com',
]);

// レコード数を確認
$this->assertDatabaseCount('customers', 5);
```

## トラブルシューティング

### テストが失敗する場合

1. データベースマイグレーションを確認
```bash
php artisan migrate:fresh --env=testing
```

2. キャッシュをクリア
```bash
php artisan config:clear
php artisan cache:clear
```

3. 詳細なエラーメッセージを表示
```bash
php artisan test --stop-on-failure
```

### パフォーマンスの問題

- SQLiteメモリデータベースを使用
- 並列実行を試す: `php artisan test --parallel`
- 不要なテストをスキップ: `$this->markTestSkipped()`

## 継続的インテグレーション（CI）

GitHub Actionsの設定例：

```yaml
name: Tests

on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest

    steps:
    - uses: actions/checkout@v2

    - name: Setup PHP
      uses: shivammathur/setup-php@v2
      with:
        php-version: '8.2'
        extensions: mbstring, pdo_sqlite
        coverage: xdebug

    - name: Install dependencies
      run: composer install --prefer-dist --no-progress

    - name: Run tests
      run: php artisan test --coverage
```

## まとめ

このガイドに従って、効果的なテストを作成し、アプリケーションの品質を維持してください。テストは以下を確認します：

- 機能が期待通りに動作すること
- リグレッション（既存機能の破壊）を防ぐこと
- コードの信頼性を高めること

質問や問題がある場合は、プロジェクトチームに相談してください。
