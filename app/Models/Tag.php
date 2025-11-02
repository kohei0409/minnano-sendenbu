<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
    ];

    // Relationships
    public function stores()
    {
        return $this->belongsToMany(Store::class, 'store_tags')
            ->withTimestamps();
    }
}
