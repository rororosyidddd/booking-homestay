<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomFacility extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'room_id',
        'name',
        'icon',
    ];

    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
