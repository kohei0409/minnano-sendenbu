<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'name',
        'email',
        'password',
        'role',
        'user_level',
        'status',
        'store_id',
        'approved_at',
        'approved_by',
        'is_pro_reviewer',
        'reviewer_bio',
        'reviewer_avatar',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'approved_at' => 'datetime',
        'password' => 'hashed',
        'is_pro_reviewer' => 'boolean',
    ];

    // リレーション
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function approvedUsers(): HasMany
    {
        return $this->hasMany(User::class, 'approved_by');
    }

    public function badges(): HasMany
    {
        return $this->hasMany(UserBadge::class);
    }

    public function activeBadges(): HasMany
    {
        return $this->hasMany(UserBadge::class)->active();
    }

    public function thematicReviews(): HasMany
    {
        return $this->hasMany(ThematicReview::class, 'writer_id');
    }

    public function publishedThematicReviews(): HasMany
    {
        return $this->hasMany(ThematicReview::class, 'writer_id')->published();
    }

    // 権限チェックメソッド
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isStoreOwner(): bool
    {
        return $this->role === 'store_owner';
    }

    public function isStoreStaff(): bool
    {
        return $this->role === 'store_staff';
    }

    public function isFreeUser(): bool
    {
        return $this->user_level === 'free';
    }

    public function isPremium1(): bool
    {
        return $this->user_level === 'premium1';
    }

    public function isPremium2(): bool
    {
        return $this->user_level === 'premium2';
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

    public function isProReviewer(): bool
    {
        return $this->is_pro_reviewer === true;
    }

    // Scopes
    public function scopeProReviewers($query)
    {
        return $query->where('is_pro_reviewer', true)
            ->where('status', 'active');
    }

    public function getReviewerAvatarUrl(): string
    {
        return $this->reviewer_avatar ?? '/images/reviewers/default-avatar.png';
    }
}
