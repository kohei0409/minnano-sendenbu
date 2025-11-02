<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'customer_id',
        'rating',
        'title',
        'content',
        'visit_date',
        'status',
        'published_at',
    ];

    protected $casts = [
        'rating' => 'decimal:2',
        'visit_date' => 'date',
        'published_at' => 'datetime',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function images()
    {
        return $this->hasMany(ReviewImage::class);
    }

    public function reply()
    {
        return $this->hasOne(ReviewReply::class);
    }

    // Scopes
    public function scopePublished($query)
    {
        return $query->where('status', 'published')
            ->whereNotNull('published_at');
    }

    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('rating', '>=', $minRating);
    }
}
