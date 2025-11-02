<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'title',
        'description',
        'discount_type',
        'discount_value',
        'code',
        'usage_limit',
        'used_count',
        'start_date',
        'end_date',
        'conditions',
        'is_active',
    ];

    protected $casts = [
        'discount_value' => 'decimal:2',
        'usage_limit' => 'integer',
        'used_count' => 'integer',
        'start_date' => 'date',
        'end_date' => 'date',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    public function usages()
    {
        return $this->hasMany(CouponUsage::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('start_date', '<=', now())
            ->where('end_date', '>=', now());
    }

    public function scopeAvailable($query)
    {
        return $query->active()
            ->whereRaw('(usage_limit IS NULL OR used_count < usage_limit)');
    }

    // Methods
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->start_date > now() || $this->end_date < now()) {
            return false;
        }

        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return false;
        }

        return true;
    }
}
