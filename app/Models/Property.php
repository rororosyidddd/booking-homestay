<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'description',
        'address',
        'city',
        'province',
        'postal_code',
        'latitude',
        'longitude',
        'type',
        'phone',
        'email',
        'website',
        'check_in_time',
        'check_out_time',
        'status',
        'cover_image',
    ];

    // Relationships
    public function owner()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function rooms()
    {
        return $this->hasMany(Room::class);
    }

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    // Average rating accessor
    public function getAverageRatingAttribute(): float
    {
        return round($this->reviews()->avg('rating') ?? 0, 1);
    }

    // Scope untuk property yang aktif
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    // Scope filter by kota
    public function scopeInCity($query, string $city)
    {
        return $query->where('city', $city);
    }
}
