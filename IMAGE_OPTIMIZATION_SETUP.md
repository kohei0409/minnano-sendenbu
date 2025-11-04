# 画像最適化機能セットアップガイド

## 概要

画像アップロード時の自動リサイズ・圧縮機能を実装しました。

## パッケージインストール

サーバー上で以下のコマンドを実行してください：

```bash
cd /home/xs509339/minnanosendenbu.jp/development/system

# Intervention Imageのインストール
composer require intervention/image

# GDライブラリの設定（config/app.phpに自動追加されます）
php artisan vendor:publish --provider="Intervention\Image\ImageServiceProviderLaravelRecent"
```

## 実装内容

### 1. ImageService クラス

`app/Services/ImageService.php` を作成しました。

**主な機能:**
- `uploadAndOptimize()`: 複数サイズの画像を生成（サムネイル、中、大）
- `uploadSingle()`: 単一サイズの最適化画像を生成
- `uploadThumbnail()`: 正方形サムネイルを生成
- `delete()`: 画像ファイルの削除

**デフォルト設定:**
- サムネイル: 300px
- 中サイズ: 800px
- 大サイズ: 1920px
- JPEG品質: 85%（オリジナルは90%）

### 2. 使用例

#### コントローラーでの使用方法

```php
use App\Services\ImageService;

class YourController extends Controller
{
    protected $imageService;

    public function __construct(ImageService $imageService)
    {
        $this->imageService = $imageService;
    }

    public function store(Request $request)
    {
        // 単一画像のアップロード（標準サイズ）
        if ($request->hasFile('image')) {
            $path = $this->imageService->uploadSingle(
                $request->file('image'),
                'store-images',
                1920  // 最大幅
            );
        }

        // 複数サイズの画像生成
        if ($request->hasFile('image')) {
            $paths = $this->imageService->uploadAndOptimize(
                $request->file('image'),
                'store-images',
                ['thumbnail' => 300, 'medium' => 800, 'large' => 1920]
            );

            // $paths['thumbnail'], $paths['medium'], $paths['large'], $paths['original']
        }

        // サムネイルのみ生成（正方形）
        if ($request->hasFile('image')) {
            $thumbnailPath = $this->imageService->uploadThumbnail(
                $request->file('image'),
                'user-avatars',
                200  // サイズ
            );
        }

        // 画像削除
        $this->imageService->delete($oldImagePath);
    }
}
```

### 3. 既存コントローラーへの適用

以下のコントローラーで画像最適化を使用するように修正できます：

- `ReviewController.php` - レビュー画像
- `Store/StoreDetailController.php` - 店舗画像
- `Store/MenuController.php` - メニュー画像

例：
```php
// 修正前
$path = $request->file('image')->store('menu-images', 'public');

// 修正後（ImageServiceを使用）
$path = $this->imageService->uploadSingle(
    $request->file('image'),
    'menu-images',
    1200
);
```

### 4. メリット

1. **ファイルサイズ削減**: 85%圧縮で高品質を保ちながらサイズを削減
2. **パフォーマンス向上**: リサイズにより読み込み速度が向上
3. **レスポンシブ対応**: 複数サイズを生成してデバイスごとに最適化
4. **ストレージ節約**: 無駄に大きな画像を保存しない
5. **セキュリティ**: ファイル名のサニタイズで安全性向上

### 5. 注意事項

- GDライブラリまたはImagickがPHPで有効になっている必要があります
- サーバーの `php.ini` で `memory_limit` が十分か確認してください（推奨: 256M以上）
- 大量の画像処理はキュー処理の使用を検討してください

### 6. サーバー要件確認

```bash
# GDライブラリの確認
php -m | grep gd

# または
php -i | grep -i gd
```

GDが表示されない場合は、サーバー管理者にGDライブラリのインストールを依頼してください。

## トラブルシューティング

### エラー: "Class 'Intervention\Image\ImageManager' not found"

```bash
composer dump-autoload
php artisan config:clear
```

### エラー: "GD Library extension not available"

GDライブラリがインストールされていません。サーバー管理者に連絡してください。

### 画像が正しく表示されない

ストレージリンクが正しく設定されているか確認：
```bash
php artisan storage:link
ls -la public/storage
```

## 今後の拡張案

- WebP形式への自動変換
- 遅延ロード（Lazy Loading）の実装
- CDNとの統合
- 画像処理のキュー化（大量アップロード時）
