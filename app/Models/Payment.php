<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'payment_code',
        'amount',
        'method',
        'provider',
        'reference_id',
        'snap_token',
        'gateway_response',
        'status',
        'paid_at',
        'expired_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'           => 'decimal:2',
            'paid_at'          => 'datetime',
            'expired_at'       => 'datetime',
            'gateway_response' => 'array',
        ];
    }

    // Auto generate payment code
    protected static function booted(): void
    {
        static::creating(function (Payment $payment) {
            $payment->payment_code = 'PAY-' . date('Ymd') . '-' . strtoupper(substr(uniqid(), -6));
        });
    }

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // Helper
    public function isPaid(): bool
    {
        return $this->status === 'paid';
    }

    public function isExpired(): bool
    {
        return $this->status === 'expired' ||
               ($this->expired_at && $this->expired_at->isPast());
    }
}
