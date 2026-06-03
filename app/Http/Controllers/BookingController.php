<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Room;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function create(Request $request)
    {
        $room = Room::with(['property', 'facilities'])->findOrFail($request->room);

        abort_if($room->status !== 'available', 404);
        abort_if($room->property->status !== 'active', 404);

        return view('bookings.create', compact('room'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'room_id'         => 'required|exists:rooms,id',
            'guest_name'      => 'required|string|max:255',
            'guest_email'     => 'required|email',
            'guest_phone'     => 'required|string|max:20',
            'guest_count'     => 'required|integer|min:1',
            'check_in'        => 'required|date|after_or_equal:today',
            'check_out'       => 'required|date|after:check_in',
            'special_request' => 'nullable|string',
        ]);

        $room         = Room::findOrFail($validated['room_id']);
        $checkIn      = $validated['check_in'];
        $checkOut     = $validated['check_out'];
        $totalNights  = (int) now()->parse($checkIn)->diffInDays($checkOut);
        $totalPrice   = $room->price_per_night * $totalNights;

        // Cek ketersediaan
        abort_if(!$room->isAvailable($checkIn, $checkOut), 422, 'Kamar tidak tersedia di tanggal tersebut.');

        $booking = Booking::create([
            'user_id'         => auth()->id(),
            'room_id'         => $room->id,
            'guest_name'      => $validated['guest_name'],
            'guest_email'     => $validated['guest_email'],
            'guest_phone'     => $validated['guest_phone'],
            'guest_count'     => $validated['guest_count'],
            'check_in'        => $checkIn,
            'check_out'       => $checkOut,
            'total_nights'    => $totalNights,
            'room_price'      => $room->price_per_night,
            'total_price'     => $totalPrice,
            'discount_amount' => 0,
            'final_price'     => $totalPrice,
            'special_request' => $validated['special_request'] ?? null,
            'status'          => 'pending',
        ]);

        \App\Models\Payment::create([
            'booking_id'  => $booking->id,
            'amount'      => $booking->final_price,
            'status'      => 'pending',
            'expired_at'  => now()->addHours(24),
        ]);

        return redirect()->route('bookings.show', $booking)
            ->with('success', 'Booking berhasil! Silakan lakukan pembayaran.');
    }

    public function show(Booking $booking)
    {
        abort_if($booking->user_id !== auth()->id(), 403);

        $booking->load(['room.property', 'payment']);

        return view('bookings.show', compact('booking'));
    }

    public function index()
    {
        $bookings = Booking::with(['room.property', 'payment'])
            ->where('user_id', auth()->id())
            ->latest()
            ->paginate(10);

        return view('bookings.index', compact('bookings'));
    }
}