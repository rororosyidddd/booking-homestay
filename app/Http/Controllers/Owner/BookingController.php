<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $query = Booking::with(['room.property', 'user', 'payment'])
            ->whereHas('room.property', fn($q) => $q->where('user_id', auth()->id()));

        // Filter status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Filter tanggal
        if ($request->filled('date')) {
            $query->whereDate('check_in', $request->date);
        }

        $bookings = $query->latest()->paginate(15);

        return view('owner.bookings.index', compact('bookings'));
    }

    public function show(Booking $booking)
    {
        abort_if($booking->room->property->user_id !== auth()->id(), 403);

        $booking->load(['room.property', 'user', 'payment', 'review']);

        return view('owner.bookings.show', compact('booking'));
    }

    public function confirm(Booking $booking)
    {
        abort_if($booking->room->property->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'pending', 422);

        $booking->update(['status' => 'confirmed']);

        return back()->with('success', 'Booking berhasil dikonfirmasi.');
    }

    public function checkIn(Booking $booking)
    {
        abort_if($booking->room->property->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'confirmed', 422);

        $booking->update(['status' => 'checked_in']);

        return back()->with('success', 'Tamu berhasil check-in.');
    }

    public function checkOut(Booking $booking)
    {
        abort_if($booking->room->property->user_id !== auth()->id(), 403);
        abort_if($booking->status !== 'checked_in', 422);

        $booking->update(['status' => 'checked_out']);

        return back()->with('success', 'Tamu berhasil check-out.');
    }
}
