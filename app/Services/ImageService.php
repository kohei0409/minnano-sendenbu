<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService
{
    /**
     * 画像をアップロードして最適化する
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param array $sizes ['thumbnail' => 200, 'medium' => 800, 'large' => 1920]
     * @return array ['original' => 'path', 'thumbnail' => 'path', ...]
     */
    public function uploadAndOptimize(UploadedFile $file, string $directory, array $sizes = []): array
    {
        $paths = [];

        // デフォルトサイズ
        if (empty($sizes)) {
            $sizes = [
                'thumbnail' => 300,
                'medium' => 800,
                'large' => 1920,
            ];
        }

        // オリジナルファイル名から拡張子を取得
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->sanitizeFilename($filename);

        // タイムスタンプを追加してユニークなファイル名を生成
        $timestamp = time();

        // 各サイズの画像を生成
        foreach ($sizes as $sizeName => $maxWidth) {
            $resizedFilename = "{$filename}_{$sizeName}_{$timestamp}.{$extension}";
            $path = "{$directory}/{$resizedFilename}";

            // 画像をリサイズして圧縮
            $image = Image::make($file->getRealPath());

            // アスペクト比を保ってリサイズ
            $image->resize($maxWidth, null, function ($constraint) {
                $constraint->aspectRatio();
                $constraint->upsize(); // 元の画像より大きくしない
            });

            // JPEG品質を85%に設定（ファイルサイズと品質のバランス）
            $image->encode($extension, 85);

            // ストレージに保存
            Storage::disk('public')->put($path, (string) $image);

            $paths[$sizeName] = $path;
        }

        // オリジナルサイズも保存（最適化のみ）
        $originalFilename = "{$filename}_original_{$timestamp}.{$extension}";
        $originalPath = "{$directory}/{$originalFilename}";

        $originalImage = Image::make($file->getRealPath());
        $originalImage->encode($extension, 90); // オリジナルは少し高品質
        Storage::disk('public')->put($originalPath, (string) $originalImage);

        $paths['original'] = $originalPath;

        return $paths;
    }

    /**
     * 単一の画像をアップロードして最適化（標準サイズ）
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $maxWidth
     * @return string
     */
    public function uploadSingle(UploadedFile $file, string $directory, int $maxWidth = 1920): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->sanitizeFilename($filename);
        $timestamp = time();

        $resizedFilename = "{$filename}_{$timestamp}.{$extension}";
        $path = "{$directory}/{$resizedFilename}";

        // 画像をリサイズして圧縮
        $image = Image::make($file->getRealPath());

        // アスペクト比を保ってリサイズ
        $image->resize($maxWidth, null, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });

        // JPEG品質を85%に設定
        $image->encode($extension, 85);

        // ストレージに保存
        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }

    /**
     * サムネイル画像のみを生成
     *
     * @param UploadedFile $file
     * @param string $directory
     * @param int $size
     * @return string
     */
    public function uploadThumbnail(UploadedFile $file, string $directory, int $size = 300): string
    {
        $extension = $file->getClientOriginalExtension();
        $filename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
        $filename = $this->sanitizeFilename($filename);
        $timestamp = time();

        $thumbnailFilename = "{$filename}_thumb_{$timestamp}.{$extension}";
        $path = "{$directory}/{$thumbnailFilename}";

        // 正方形のサムネイルを生成
        $image = Image::make($file->getRealPath());

        // 中央から正方形で切り抜き
        $image->fit($size, $size);

        // JPEG品質を85%に設定
        $image->encode($extension, 85);

        // ストレージに保存
        Storage::disk('public')->put($path, (string) $image);

        return $path;
    }

    /**
     * ファイル名をサニタイズ
     *
     * @param string $filename
     * @return string
     */
    private function sanitizeFilename(string $filename): string
    {
        // 特殊文字を削除してアンダースコアに置換
        $filename = preg_replace('/[^A-Za-z0-9\-_]/', '_', $filename);

        // 長すぎる場合は切り詰め
        if (strlen($filename) > 50) {
            $filename = substr($filename, 0, 50);
        }

        return $filename;
    }

    /**
     * 画像を削除
     *
     * @param string|array $paths
     * @return bool
     */
    public function delete($paths): bool
    {
        if (is_string($paths)) {
            $paths = [$paths];
        }

        foreach ($paths as $path) {
            if (Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }

        return true;
    }
}
