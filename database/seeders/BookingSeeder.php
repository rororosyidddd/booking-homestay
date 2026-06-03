<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\Room;
use App\Models\User;
use Illuminate\Database\Seeder;

class BookingSeeder extends Seeder
{
    public function run(): void
    {
        $guest1 = User::where('email', 'andi@gmail.com')->first();
        $guest2 = User::where('email', 'rina@gmail.com')->first();

        $rooms = Room::all();
        $room1 = $rooms[0]; // Kamar Standar Hotel
        $room2 = $rooms[1]; // Kamar Deluxe Hotel
        $room3 = $rooms[3]; // Villa 1 Kamar
        $room4 = $rooms[5]; // Kamar Ekonomi Guest House

        $bookings = [
            [
                'user'           => $guest1,
                'room'           => $room1,
                'guest_name'     => 'Andi Wijaya',
                'guest_email'    => 'andi@gmail.com',
                'guest_phone'    => '081200000004',
                'guest_count'    => 2,
                'check_in'       => now()->addDays(3)->format('Y-m-d'),
                'check_out'      => now()->addDays(5)->format('Y-m-d'),
                'total_nights'   => 2,
                'room_price'     => 250000,
                'total_price'    => 500000,
                'discount_amount'=> 0,
                'final_price'    => 500000,
                'status'         => 'confirmed',
                'payment_status' => 'paid',
            ],
            [
                'user'           => $guest2,
                'room'           => $room2,
                'guest_name'     => 'Rina Kusuma',
                'guest_email'    => 'rina@gmail.com',
                'guest_phone'    => '081200000005',
                'guest_count'    => 2,
                'check_in'       => now()->addDays(7)->format('Y-m-d'),
                'check_out'      => now()->addDays(10)->format('Y-m-d'),
                'total_nights'   => 3,
                'room_price'     => 400000,
                'total_price'    => 1200000,
                'discount_amount'=> 0,
                'final_price'    => 1200000,
                'status'         => 'pending',
                'payment_status' => 'pending',
            ],
            [
                'user'           => $guest1,
                'room'           => $room3,
                'guest_name'     => 'Andi Wijaya',
                'guest_email'    => 'andi@gmail.com',
                'guest_phone'    => '081200000004',
                'guest_count'    => 2,
                'check_in'       => now()->format('Y-m-d'),
                'check_out'      => now()->addDays(2)->format('Y-m-d'),
                'total_nights'   => 2,
                'room_price'     => 1200000,
                'total_price'    => 2400000,
                'discount_amount'=> 200000,
                'final_price'    => 2200000,
                'status'         => 'checked_in',
                'payment_status' => 'paid',
            ],
            [
                'user'           => $guest2,
                'room'           => $room4,
                'guest_name'     => 'Rina Kusuma',
                'guest_email'    => 'rina@gmail.com',
                'guest_phone'    => '081200000005',
                'guest_count'    => 1,
                'check_in'       => now()->subDays(5)->format('Y-m-d'),
                'check_out'      => now()->subDays(3)->format('Y-m-d'),
                'total_nights'   => 2,
                'room_price'     => 120000,
                'total_price'    => 240000,
                'discount_amount'=> 0,
                'final_price'    => 240000,
                'status'         => 'checked_out',
                'payment_status' => 'paid',
            ],
            [
                'user'           => $guest1,
                'room'           => $room2,
                'guest_name'     => 'Andi Wijaya',
                'guest_email'    => 'andi@gmail.com',
                'guest_phone'    => '081200000004',
                'guest_count'    => 2,
                'check_in'       => now()->addDays(14)->format('Y-m-d'),
                'check_out'      => now()->addDays(16)->format('Y-m-d'),
                'total_nights'   => 2,
                'room_price'     => 400000,
                'total_price'    => 800000,
                'discount_amount'=> 0,
                'final_price'    => 800000,
                'status'         => 'cancelled',
                'cancel_reason'  => 'Perubahan rencana perjalanan',
                'cancelled_at'   => now()->subDay(),
                'payment_status' => 'failed',
            ],
        ];

        foreach ($bookings as $data) {
           $user          = $data['user'];
           $room          = $data['room'];
           $paymentStatus = $data['payment_status'];
           unset($data['user'], $data['room'], $data['payment_status']);
           
           $booking = Booking::create(array_merge($data, [
            'user_id' => $user->id,
            'room_id' => $room->id,
            ]));

            
            Payment::create([
                'booking_id'   => $booking->id,
                'amount'       => $booking->final_price,
                'method'       => $paymentStatus === 'paid' ? 'transfer' : null,
                'provider'     => $paymentStatus === 'paid' ? 'midtrans' : null,
                'reference_id' => $paymentStatus === 'paid' ? 'REF-' . strtoupper(uniqid()) : null,
                'status'       => $paymentStatus,
                'paid_at'      => $paymentStatus === 'paid' ? now() : null,
            ]);
        }
    }
}
