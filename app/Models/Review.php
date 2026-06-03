<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'user_id',
        'property_id',
        'rating',
        'cleanliness_rating',
        'comfort_rating',
        'location_rating',
        'service_rating',
        'comment',
        'owner_reply',
        'replied_at',
    ];

    protected function casts(): array
    {
        return [
            'replied_at' => 'datetime',
        ];
    }

    // Relationships
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    // Average detail rating
    public function getDetailAverageAttribute(): float
    {
        $ratings = array_filter([
            $this->cleanliness_rating,
            $this->comfort_rating,
            $this->location_rating,
            $this->service_rating,
        ]);

        return count($ratings) ? round(array_sum($ratings) / count($ratings), 1) : 0;
    }
}
