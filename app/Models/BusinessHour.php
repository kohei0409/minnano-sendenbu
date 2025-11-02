<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BusinessHour extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'day_of_week',
        'is_closed',
        'open_time',
        'close_time',
        'break_start',
        'break_end',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_closed' => 'boolean',
    ];

    // Relationships
    public function store()
    {
        return $this->belongsTo(Store::class);
    }

    // Scopes
    public function scopeOpen($query)
    {
        return $query->where('is_closed', false);
    }

    public function scopeForDay($query, $day)
    {
        return $query->where('day_of_week', $day);
    }
}
