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
    ];

    protected $casts = [
        'display_order' => 'integer',
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
}
