<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Property;
use App\Models\User;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_users'      => User::count(),
            'total_owners'     => User::where('role', 'owner')->count(),
            'total_guests'     => User::where('role', 'guest')->count(),
            'total_properties' => Property::count(),
            'pending_properties' => Property::where('status', 'pending')->count(),
            'total_bookings'   => Booking::count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'total_revenue'    => Booking::where('status', 'confirmed')->sum('final_price'),
        ];

        $recentBookings = Booking::with(['room.property', 'user'])
            ->latest()
            ->take(5)
            ->get();

        $pendingProperties = Property::with('owner')
            ->where('status', 'pending')
            ->latest()
            ->take(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'recentBookings', 'pendingProperties'));
    }
}