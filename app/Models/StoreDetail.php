<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreDetail extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'description',
        'access_info',
        'parking_info',
        'payment_methods',
        'website_url',
        'facebook_url',
        'instagram_url',
        'twitter_url',
        'seats',
        'private_rooms',
        'smoking',
        'wifi',
        'power_outlet',
        'credit_card',
    ];

    protected $casts = [
        'seats' => 'integer',
        'private_rooms' => 'boolean',
        'wifi' => 'boolean',
        'power_outlet' => 'boolean',
        'credit_card' => 'boolean',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }
}
