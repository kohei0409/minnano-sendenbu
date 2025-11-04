<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
// use Laravel\Scout\Searchable; // Uncomment after installing: composer require laravel/scout

class Store extends Model
{
    use HasFactory, SoftDeletes; // , Searchable; // Uncomment after installing Laravel Scout

    protected $fillable = [
        'store_name',
        'industry',
        'contact_name',
        'email',
        'phone',
        'address',
        'description',
        'status',
        // Extended store fields
        'category_id',
        'area_id',
        'postal_code',
        'prefecture',
        'city',
        'street_address',
        'building',
        'latitude',
        'longitude',
        'average_rating',
        'review_count',
        'view_count',
        'favorite_count',
        'is_featured',
        'featured_until',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'deleted_at' => 'datetime',
        'latitude' => 'decimal:8',
        'longitude' => 'decimal:8',
        'average_rating' => 'decimal:2',
        'review_count' => 'integer',
        'view_count' => 'integer',
        'favorite_count' => 'integer',
        'is_featured' => 'boolean',
        'featured_until' => 'datetime',
    ];

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }

    public function owner()
    {
        return $this->users()->where('role', 'store_owner')->first();
    }

    public function staff(): HasMany
    {
        return $this->users()->where('role', 'store_staff');
    }

    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isSuspended(): bool
    {
        return $this->status === 'suspended';
    }

    // Extended Relationships
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function area()
    {
        return $this->belongsTo(Area::class);
    }

    public function storeDetail()
    {
        return $this->hasOne(StoreDetail::class);
    }

    public function images()
    {
        return $this->hasMany(StoreImage::class)->orderBy('display_order');
    }

    public function businessHours()
    {
        return $this->hasMany(BusinessHour::class)->orderBy('day_of_week');
    }

    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function publishedReviews()
    {
        return $this->reviews()->published()->orderBy('published_at', 'desc');
    }

    public function coupons()
    {
        return $this->hasMany(Coupon::class);
    }

    public function activeCoupons()
    {
        return $this->coupons()->active();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function reservationSlots()
    {
        return $this->hasMany(ReservationSlot::class);
    }

    public function externalCoupons()
    {
        return $this->hasMany(ExternalCoupon::class);
    }

    public function activeExternalCoupons()
    {
        return $this->externalCoupons()->active();
    }

    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_stores')
            ->withTimestamps();
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(Customer::class, 'favorites')
            ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'store_tags')
            ->withTimestamps();
    }

    public function sns()
    {
        return $this->hasMany(StoreSns::class);
    }

    public function activeSns()
    {
        return $this->hasMany(StoreSns::class)->active();
    }

    public function thematicReviews()
    {
        return $this->belongsToMany(ThematicReview::class, 'thematic_review_stores')
            ->withPivot('rank', 'comment')
            ->withTimestamps();
    }

    // Query Scopes
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true)
            ->where('featured_until', '>=', now());
    }

    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByArea($query, $areaId)
    {
        return $query->where('area_id', $areaId);
    }

    public function scopeHighRated($query, $minRating = 4)
    {
        return $query->where('average_rating', '>=', $minRating);
    }

    public function scopeWithReviews($query)
    {
        return $query->where('review_count', '>', 0);
    }

    // Helper Methods
    public function incrementViewCount()
    {
        $this->increment('view_count');
    }

    public function updateRating()
    {
        $average = $this->reviews()->published()->avg('rating');
        $count = $this->reviews()->published()->count();

        $this->update([
            'average_rating' => $average ?? 0,
            'review_count' => $count,
        ]);
    }

    public function updateFavoriteCount()
    {
        $count = $this->favoritedBy()->count();
        $this->update(['favorite_count' => $count]);
    }

    // Laravel Scout methods - Uncomment after installing: composer require laravel/scout
    /*
    public function toSearchableArray(): array
    {
        // Load storeDetail if not already loaded
        if (!$this->relationLoaded('storeDetail')) {
            $this->load('storeDetail');
        }

        return [
            'id' => $this->id,
            'store_name' => $this->store_name,
            'industry' => $this->industry,
            'street_address' => $this->street_address,
            'city' => $this->city,
            'prefecture' => $this->prefecture,
            'description' => $this->storeDetail?->description ?? '',
            'catchphrase' => $this->storeDetail?->catchphrase ?? '',
        ];
    }

    public function getScoutKey(): mixed
    {
        return $this->id;
    }

    public function getScoutKeyName(): mixed
    {
        return 'id';
    }
    */
}
