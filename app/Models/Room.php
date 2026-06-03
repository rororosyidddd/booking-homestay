<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    use HasFactory;

    protected $fillable = [
        'property_id',
        'name',
        'description',
        'capacity',
        'price_per_night',
        'weekend_price',
        'total_rooms',
        'bed_type',
        'size_sqm',
        'status',
    ];

    protected function casts(): array
    {
        return [
            'price_per_night' => 'decimal:2',
            'weekend_price'   => 'decimal:2',
        ];
    }

    // Relationships
    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function images()
    {
        return $this->hasMany(RoomImage::class);
    }

    public function primaryImage()
    {
        return $this->hasOne(RoomImage::class)->where('is_primary', true);
    }

    public function facilities()
    {
        return $this->hasMany(RoomFacility::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // Cek apakah kamar tersedia di tanggal tertentu
    public function isAvailable(string $checkIn, string $checkOut): bool
    {
        $bookedCount = $this->bookings()
            ->whereIn('status', ['confirmed', 'checked_in'])
            ->where(function ($query) use ($checkIn, $checkOut) {
                $query->whereBetween('check_in', [$checkIn, $checkOut])
                    ->orWhereBetween('check_out', [$checkIn, $checkOut])
                    ->orWhere(function ($q) use ($checkIn, $checkOut) {
                        $q->where('check_in', '<=', $checkIn)
                          ->where('check_out', '>=', $checkOut);
                    });
            })->count();

        return $bookedCount < $this->total_rooms;
    }

    // Harga berdasarkan hari (weekend/weekday)
    public function getPriceForDate(string $date): float
    {
        $dayOfWeek = date('N', strtotime($date));
        $isWeekend = in_array($dayOfWeek, [6, 7]);

        if ($isWeekend && $this->weekend_price) {
            return (float) $this->weekend_price;
        }

        return (float) $this->price_per_night;
    }
}
