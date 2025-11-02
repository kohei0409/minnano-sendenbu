<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'store_id',
        'customer_id',
        'reservation_date',
        'reservation_time',
        'number_of_people',
        'customer_name',
        'customer_phone',
        'customer_email',
        'message',
        'status',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'number_of_people' => 'integer',
        'confirmed_at' => 'datetime',
        'cancelled_at' => 'datetime',
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

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    public function scopeUpcoming($query)
    {
        return $query->where('reservation_date', '>=', now()->toDateString())
            ->whereIn('status', ['pending', 'confirmed'])
            ->orderBy('reservation_date')
            ->orderBy('reservation_time');
    }
}
