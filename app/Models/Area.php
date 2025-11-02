<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    use HasFactory;

    protected $fillable = [
        'parent_id',
        'name',
        'slug',
        'level',
    ];

    protected $casts = [
        'level' => 'integer',
    ];

    // Relationships
    public function parent()
    {
        return $this->belongsTo(Area::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Area::class, 'parent_id');
    }

    public function stores()
    {
        return $this->hasMany(Store::class);
    }

    // Scopes
    public function scopePrefectures($query)
    {
        return $query->where('level', 1)
            ->orderBy('name');
    }

    public function scopeCities($query)
    {
        return $query->where('level', 2)
            ->orderBy('name');
    }
}
