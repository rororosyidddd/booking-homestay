<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\Room;
use App\Models\RoomFacility;
use App\Models\User;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        $owner1 = User::where('email', 'budi@homestay.com')->first();
        $owner2 = User::where('email', 'siti@homestay.com')->first();

        // =====================
        // Properti 1 - Hotel
        // =====================
        $hotel = Property::create([
            'user_id'        => $owner1->id,
            'name'           => 'Hotel Melati Yogyakarta',
            'description'    => 'Hotel nyaman di pusat kota Yogyakarta, dekat dengan Malioboro dan Keraton. Dilengkapi fasilitas modern dengan nuansa budaya Jawa yang kental.',
            'address'        => 'Jl. Malioboro No. 123',
            'city'           => 'Yogyakarta',
            'province'       => 'DI Yogyakarta',
            'postal_code'    => '55271',
            'type'           => 'hotel',
            'phone'          => '027412345',
            'email'          => 'info@hotelmelati.com',
            'check_in_time'  => '14:00',
            'check_out_time' => '12:00',
            'status'         => 'active',
        ]);

        $this->createRooms($hotel, [
            [
                'name'            => 'Kamar Standar',
                'description'     => 'Kamar nyaman dengan tempat tidur queen size dan pemandangan kota.',
                'capacity'        => 2,
                'price_per_night' => 250000,
                'weekend_price'   => 300000,
                'total_rooms'     => 5,
                'bed_type'        => 'Queen Bed',
                'size_sqm'        => 20,
                'facilities'      => ['AC', 'TV', 'WiFi', 'Kamar Mandi Dalam', 'Air Panas'],
            ],
            [
                'name'            => 'Kamar Deluxe',
                'description'     => 'Kamar luas dengan fasilitas lengkap dan pemandangan taman.',
                'capacity'        => 2,
                'price_per_night' => 400000,
                'weekend_price'   => 500000,
                'total_rooms'     => 3,
                'bed_type'        => 'King Bed',
                'size_sqm'        => 30,
                'facilities'      => ['AC', 'TV', 'WiFi', 'Kamar Mandi Dalam', 'Air Panas', 'Bathtub', 'Mini Bar'],
            ],
            [
                'name'            => 'Suite Room',
                'description'     => 'Kamar suite mewah dengan ruang tamu terpisah dan pemandangan terbaik.',
                'capacity'        => 4,
                'price_per_night' => 750000,
                'weekend_price'   => 900000,
                'total_rooms'     => 2,
                'bed_type'        => 'King Bed',
                'size_sqm'        => 50,
                'facilities'      => ['AC', 'TV', 'WiFi', 'Kamar Mandi Dalam', 'Air Panas', 'Bathtub', 'Mini Bar', 'Sofa', 'Kulkas'],
            ],
        ]);

        // =====================
        // Properti 2 - Villa
        // =====================
        $villa = Property::create([
            'user_id'        => $owner1->id,
            'name'           => 'Villa Sawah Ubud Bali',
            'description'    => 'Villa eksklusif di tengah sawah Ubud dengan pemandangan alam yang memukau. Cocok untuk bulan madu dan liburan keluarga.',
            'address'        => 'Jl. Raya Ubud No. 88',
            'city'           => 'Ubud',
            'province'       => 'Bali',
            'postal_code'    => '80571',
            'type'           => 'villa',
            'phone'          => '036188888',
            'email'          => 'info@villasawah.com',
            'check_in_time'  => '15:00',
            'check_out_time' => '11:00',
            'status'         => 'active',
        ]);

        $this->createRooms($villa, [
            [
                'name'            => 'Villa 1 Kamar',
                'description'     => 'Villa private dengan 1 kamar tidur, kolam renang pribadi, dan pemandangan sawah.',
                'capacity'        => 2,
                'price_per_night' => 1200000,
                'weekend_price'   => 1500000,
                'total_rooms'     => 3,
                'bed_type'        => 'King Bed',
                'size_sqm'        => 80,
                'facilities'      => ['AC', 'TV', 'WiFi', 'Kolam Renang Pribadi', 'Dapur Kecil', 'Teras', 'Sarapan'],
            ],
            [
                'name'            => 'Villa 2 Kamar',
                'description'     => 'Villa luas dengan 2 kamar tidur, ideal untuk keluarga kecil.',
                'capacity'        => 4,
                'price_per_night' => 2000000,
                'weekend_price'   => 2500000,
                'total_rooms'     => 2,
                'bed_type'        => 'King Bed + Twin Bed',
                'size_sqm'        => 150,
                'facilities'      => ['AC', 'TV', 'WiFi', 'Kolam Renang Pribadi', 'Dapur Lengkap', 'Teras', 'Sarapan', 'BBQ Area'],
            ],
        ]);

        // =====================
        // Properti 3 - Guest House
        // =====================
        $guesthouse = Property::create([
            'user_id'        => $owner2->id,
            'name'           => 'Guest House Anggrek Bandung',
            'description'    => 'Guest house bersih dan nyaman di kawasan strategis Bandung. Cocok untuk backpacker dan wisatawan budget.',
            'address'        => 'Jl. Dago No. 45',
            'city'           => 'Bandung',
            'province'       => 'Jawa Barat',
            'postal_code'    => '40135',
            'type'           => 'guest_house',
            'phone'          => '022987654',
            'email'          => 'info@ghanggrek.com',
            'check_in_time'  => '13:00',
            'check_out_time' => '12:00',
            'status'         => 'active',
        ]);

        $this->createRooms($guesthouse, [
            [
                'name'            => 'Kamar Ekonomi',
                'description'     => 'Kamar sederhana bersih dengan fasilitas dasar.',
                'capacity'        => 2,
                'price_per_night' => 120000,
                'weekend_price'   => 150000,
                'total_rooms'     => 6,
                'bed_type'        => 'Single Bed',
                'size_sqm'        => 12,
                'facilities'      => ['Kipas Angin', 'WiFi', 'Kamar Mandi Sharing'],
            ],
            [
                'name'            => 'Kamar Standar',
                'description'     => 'Kamar standar dengan kamar mandi dalam dan AC.',
                'capacity'        => 2,
                'price_per_night' => 200000,
                'weekend_price'   => 250000,
                'total_rooms'     => 4,
                'bed_type'        => 'Double Bed',
                'size_sqm'        => 16,
                'facilities'      => ['AC', 'WiFi', 'Kamar Mandi Dalam', 'TV'],
            ],
        ]);
    }

    private function createRooms(Property $property, array $roomsData): void
    {
        foreach ($roomsData as $data) {
            $facilities = $data['facilities'] ?? [];
            unset($data['facilities']);

            $room = Room::create(array_merge($data, ['property_id' => $property->id]));

            foreach ($facilities as $facility) {
                RoomFacility::create([
                    'room_id' => $room->id,
                    'name'    => $facility,
                ]);
            }
        }
    }
}
