@extends('layouts.owner')

@section('title', 'Detail Booking')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.bookings.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Detail Booking</h2>
    <p class="text-gray-500 text-sm">{{ $booking->booking_code }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

    {{-- Info Booking --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Tamu --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">Informasi Tamu</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Nama</p>
                    <p class="font-medium text-gray-700">{{ $booking->guest_name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Email</p>
                    <p class="font-medium text-gray-700">{{ $booking->guest_email }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Telepon</p>
                    <p class="font-medium text-gray-700">{{ $booking->guest_phone }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Jumlah Tamu</p>
                    <p class="font-medium text-gray-700">{{ $booking->guest_count }} orang</p>
                </div>
            </div>
            @if($booking->special_request)
            <div class="mt-4">
                <p class="text-gray-400 text-sm">Permintaan Khusus</p>
                <p class="text-sm text-gray-700 mt-1 bg-gray-50 rounded-lg p-3">{{ $booking->special_request }}</p>
            </div>
            @endif
        </div>

        {{-- Detail Booking --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">Detail Menginap</h3>
            <div class="grid grid-cols-2 gap-4 text-sm">
                <div>
                    <p class="text-gray-400">Properti</p>
                    <p class="font-medium text-gray-700">{{ $booking->room->property->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Kamar</p>
                    <p class="font-medium text-gray-700">{{ $booking->room->name }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Check-in</p>
                    <p class="font-medium text-gray-700">{{ $booking->check_in->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Check-out</p>
                    <p class="font-medium text-gray-700">{{ $booking->check_out->format('d M Y') }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Lama Menginap</p>
                    <p class="font-medium text-gray-700">{{ $booking->total_nights }} malam</p>
                </div>
                <div>
                    <p class="text-gray-400">Harga per Malam</p>
                    <p class="font-medium text-gray-700">Rp {{ number_format($booking->room_price, 0, ',', '.') }}</p>
                </div>
            </div>

            <div class="mt-4 pt-4 border-t text-sm space-y-2">
                <div class="flex justify-between text-gray-600">
                    <span>Total Harga</span>
                    <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
                </div>
                @if($booking->discount_amount > 0)
                <div class="flex justify-between text-green-600">
                    <span>Diskon</span>
                    <span>- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
                </div>
                @endif
                <div class="flex justify-between font-semibold text-gray-800 text-base pt-2 border-t">
                    <span>Total Bayar</span>
                    <span>Rp {{ number_format($booking->final_price, 0, ',', '.') }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Sidebar Status & Aksi --}}
    <div class="space-y-5">
        {{-- Status --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Status Booking</h3>
            @php
                $colors = [
                    'pending'     => 'bg-yellow-100 text-yellow-700',
                    'confirmed'   => 'bg-blue-100 text-blue-700',
                    'checked_in'  => 'bg-green-100 text-green-700',
                    'checked_out' => 'bg-gray-100 text-gray-600',
                    'cancelled'   => 'bg-red-100 text-red-600',
                ];
            @endphp
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $colors[$booking->status] ?? '' }}">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>

            <div class="mt-4 space-y-2">
                @if($booking->status === 'pending')
                <form method="POST" action="{{ route('owner.bookings.confirm', $booking) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-blue-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-blue-700">
                        Konfirmasi Booking
                    </button>
                </form>
                @endif

                @if($booking->status === 'confirmed')
                <form method="POST" action="{{ route('owner.bookings.check-in', $booking) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                        Check-in Tamu
                    </button>
                </form>
                @endif

                @if($booking->status === 'checked_in')
                <form method="POST" action="{{ route('owner.bookings.check-out', $booking) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-gray-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-700">
                        Check-out Tamu
                    </button>
                </form>
                @endif
            </div>
        </div>

        {{-- Pembayaran --}}
        @if($booking->payment)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Pembayaran</h3>
            <div class="space-y-2 text-sm">
                <div class="flex justify-between">
                    <span class="text-gray-400">Kode</span>
                    <span class="font-mono text-xs text-gray-600">{{ $booking->payment->payment_code }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Metode</span>
                    <span class="text-gray-700">{{ $booking->payment->method ?? '-' }}</span>
                </div>
                <div class="flex justify-between">
                    <span class="text-gray-400">Status</span>
                    <span class="{{ $booking->payment->isPaid() ? 'text-green-600' : 'text-yellow-600' }} font-medium">
                        {{ ucfirst($booking->payment->status) }}
                    </span>
                </div>
                @if($booking->payment->paid_at)
                <div class="flex justify-between">
                    <span class="text-gray-400">Dibayar</span>
                    <span class="text-gray-700">{{ $booking->payment->paid_at->format('d M Y H:i') }}</span>
                </div>
                @endif
            </div>
        </div>
        @endif
    </div>
</div>
@endsection
