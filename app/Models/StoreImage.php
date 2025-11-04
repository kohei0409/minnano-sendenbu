<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'image_path',
        'image_type',
        'caption',
        'display_order',
        'media_type',
        'duration',
        'thumbnail_path',
        'file_size',
        'mime_type',
    ];

    protected $casts = [
        'display_order' => 'integer',
        'duration' => 'integer',
        'file_size' => 'integer',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Scopes
    public function scopeByType($query, $type)
    {
        return $query->where('image_type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('display_order');
    }

    public function scopeVideos($query)
    {
        return $query->where('media_type', 'video');
    }

    public function scopePhotos($query)
    {
        return $query->where('media_type', 'photo')
            ->orWhereNull('media_type');
    }

    // Helper methods
    public function isVideo(): bool
    {
        return $this->media_type === 'video';
    }

    public function isPhoto(): bool
    {
        return $this->media_type === 'photo' || $this->media_type === null;
    }

    /**
     * 動画のサムネイル、または写真のパスを取得
     *
     * @return string
     */
    public function getDisplayPath(): string
    {
        if ($this->isVideo() && $this->thumbnail_path) {
            return $this->thumbnail_path;
        }

        return $this->image_path;
    }

    /**
     * ファイルサイズを人間が読める形式で取得
     *
     * @return string
     */
    public function getFormattedFileSize(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * 動画の長さを分:秒形式で取得
     *
     * @return string
     */
    public function getFormattedDuration(): string
    {
        if (!$this->duration) {
            return 'N/A';
        }

        $minutes = floor($this->duration / 60);
        $seconds = $this->duration % 60;

        return sprintf('%d:%02d', $minutes, $seconds);
    }
}
