<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'name',
        'description',
        'price',
        'category',
        'image_path',
        'is_available',
        'average_rating',
        'review_count',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_available' => 'boolean',
        'average_rating' => 'decimal:2',
        'review_count' => 'integer',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function reviews()
    {
        return $this->hasMany(MenuReview::class);
    }

    // Scopes
    public function scopeAvailable($query)
    {
        return $query->where('is_available', true);
    }

    public function scopeByCategory($query, $category)
    {
        return $query->where('category', $category);
    }

    public function scopeHighRated($query, $minRating = 4.0)
    {
        return $query->where('average_rating', '>=', $minRating);
    }

    // Methods
    /**
     * メニューの平均評価とレビュー数を更新
     *
     * @return void
     */
    public function updateRating(): void
    {
        $reviews = $this->reviews();

        $this->review_count = $reviews->count();
        $this->average_rating = $this->review_count > 0
            ? round($reviews->avg('rating'), 2)
            : null;

        $this->save();
    }

    /**
     * 星の表示用（整数部分）
     *
     * @return int
     */
    public function getFullStars(): int
    {
        return (int) floor($this->average_rating ?? 0);
    }

    /**
     * 半星があるかチェック
     *
     * @return bool
     */
    public function hasHalfStar(): bool
    {
        if (!$this->average_rating) {
            return false;
        }

        $decimal = $this->average_rating - floor($this->average_rating);
        return $decimal >= 0.25 && $decimal < 0.75;
    }

    /**
     * 空星の数
     *
     * @return int
     */
    public function getEmptyStars(): int
    {
        $fullStars = $this->getFullStars();
        $halfStar = $this->hasHalfStar() ? 1 : 0;

        return 5 - $fullStars - $halfStar;
    }
}
