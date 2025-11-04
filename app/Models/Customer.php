<?php

namespace App\Models;

use Illuminate\Auth\Passwords\CanResetPassword;
use Illuminate\Contracts\Auth\CanResetPassword as CanResetPasswordContract;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable implements CanResetPasswordContract
{
    use HasFactory, Notifiable, CanResetPassword;

    protected $fillable = [
        'name',
        'email',
        'password',
        'nickname',
        'avatar',
        'birthday',
        'gender',
        'prefecture',
        'city',
        'phone',
        'email_verified_at',
        'status',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'birthday' => 'date',
        'password' => 'hashed',
    ];

    // Relationships
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function menuReviews()
    {
        return $this->hasMany(MenuReview::class);
    }

    public function favorites()
    {
        return $this->belongsToMany(Store::class, 'favorites')
            ->withTimestamps();
    }

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function couponUsages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    public function badges()
    {
        return $this->hasMany(CustomerBadge::class);
    }

    public function activeBadges()
    {
        return $this->hasMany(CustomerBadge::class)->active();
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Send the password reset notification.
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\CustomerResetPasswordNotification($token));
    }

    // Helper Methods
    public function getBadges()
    {
        return $this->activeBadges()->get();
    }

    public function getReviewStats(): array
    {
        $totalReviews = $this->reviews()->count() + $this->menuReviews()->count();
        $helpfulCount = $this->reviews()->sum('helpful_count');
        $averageRating = $this->reviews()->avg('rating');

        return [
            'total_reviews' => $totalReviews,
            'helpful_count' => $helpfulCount,
            'average_rating' => round($averageRating, 1),
        ];
    }

    public function hasReviewBadge(string $badgeType): bool
    {
        return $this->activeBadges()
            ->where('badge_type', $badgeType)
            ->exists();
    }
}
