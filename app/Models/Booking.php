<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_code',
        'user_id',
        'room_id',
        'guest_name',
        'guest_email',
        'guest_phone',
        'guest_count',
        'check_in',
        'check_out',
        'total_nights',
        'room_price',
        'total_price',
        'discount_amount',
        'final_price',
        'special_request',
        'status',
        'cancelled_at',
        'cancel_reason',
    ];

    protected function casts(): array
    {
        return [
            'check_in'        => 'date',
            'check_out'       => 'date',
            'cancelled_at'    => 'datetime',
            'room_price'      => 'decimal:2',
            'total_price'     => 'decimal:2',
            'discount_amount' => 'decimal:2',
            'final_price'     => 'decimal:2',
        ];
    }

    // Auto generate booking code
    protected static function booted(): void
    {
        static::creating(function (Booking $booking) {
            $booking->booking_code = 'BKG-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -4));
        });
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    public function payment()
    {
        return $this->hasOne(Payment::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class);
    }

    // Scope by status
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('status', 'confirmed');
    }

    // Helper
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isCancellable(): bool
    {
        return in_array($this->status, ['pending', 'confirmed']);
    }
}
