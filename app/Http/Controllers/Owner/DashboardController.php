<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $owner = auth()->user();

        // Ambil semua property milik owner
        $properties = Property::where('user_id', $owner->id)->pluck('id');

        // Statistik
        $stats = [
            'total_properties' => $properties->count(),
            'total_bookings'   => Booking::whereHas('room.property', fn($q) => $q->where('user_id', $owner->id))->count(),
            'pending_bookings' => Booking::whereHas('room.property', fn($q) => $q->where('user_id', $owner->id))->where('status', 'pending')->count(),
            'total_revenue'    => Booking::whereHas('room.property', fn($q) => $q->where('user_id', $owner->id))->where('status', 'confirmed')->sum('final_price'),
        ];

        // Booking terbaru
        $recentBookings = Booking::with(['room.property', 'user'])
            ->whereHas('room.property', fn($q) => $q->where('user_id', $owner->id))
            ->latest()
            ->take(5)
            ->get();

        return view('owner.dashboard', compact('stats', 'recentBookings'));
    }
}
