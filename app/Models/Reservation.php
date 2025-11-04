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
        'reservation_slot_id',
        'reservation_date',
        'reservation_time',
        'number_of_people',
        'customer_name',
        'customer_phone',
        'customer_email',
        'message',
        'status',
        'source',
        'external_id',
        'external_data',
        'confirmed_at',
        'cancelled_at',
    ];

    protected $casts = [
        'reservation_date' => 'date',
        'number_of_people' => 'integer',
        'external_data' => 'array',
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

    public function reservationSlot()
    {
        return $this->belongsTo(ReservationSlot::class);
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

    public function scopeFromWeb($query)
    {
        return $query->where('source', 'web');
    }

    public function scopeFromExternal($query)
    {
        return $query->where('source', '!=', 'web')
            ->whereNotNull('source');
    }

    public function scopeBySource($query, $source)
    {
        return $query->where('source', $source);
    }

    // Helper Methods
    public function isFromExternal(): bool
    {
        return !empty($this->source) && $this->source !== 'web';
    }

    public function getSourceName(): string
    {
        return match($this->source) {
            'web' => 'ウェブサイト',
            'google' => 'Google予約',
            'api' => 'API連携',
            'phone' => '電話予約',
            default => $this->source ?? '未設定',
        };
    }
}
