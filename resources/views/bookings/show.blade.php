@extends('layouts.app')

@section('title', 'Detail Booking')

@section('content')
<div class="max-w-3xl mx-auto px-4 py-10">

    <div class="mb-6">
        <a href="{{ route('bookings.index') }}" class="text-sm text-indigo-600 hover:underline">← Riwayat Booking</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Detail Booking</h1>
        <p class="text-gray-500 text-sm">{{ $booking->booking_code }}</p>
    </div>

    @if(session('success'))
        <div class="mb-6 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
            {{ session('success') }}
        </div>
    @endif

    {{-- Status --}}
    @php
        $colors = [
            'pending'     => 'bg-yellow-100 text-yellow-700',
            'confirmed'   => 'bg-blue-100 text-blue-700',
            'checked_in'  => 'bg-green-100 text-green-700',
            'checked_out' => 'bg-gray-100 text-gray-600',
            'cancelled'   => 'bg-red-100 text-red-600',
        ];
    @endphp

    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-gray-700">Status Booking</h2>
            <span class="px-3 py-1 rounded-full text-sm font-medium {{ $colors[$booking->status] ?? '' }}">
                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
            </span>
        </div>

        @if($booking->status === 'pending')
        <p class="text-sm text-yellow-600 mt-3">
            ⚠️ Silahkan lakukan pembayaran sebelum
            {{ $booking->payment?->expired_at?->format('d M Y H:i') ?? '-' }}
        </p>
        @endif
    </div>

    {{-- Detail Menginap --}}
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 mb-5">
        <h2 class="font-semibold text-gray-700 mb-4">Detail Menginap</h2>
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
                <p class="text-gray-400">Jumlah Tamu</p>
                <p class="font-medium text-gray-700">{{ $booking->guest_count }} orang</p>
            </div>
        </div>

        <div class="mt-4 pt-4 border-t text-sm space-y-2">
            <div class="flex justify-between text-gray-600">
                <span>Rp {{ number_format($booking->room_price, 0, ',', '.') }} x {{ $booking->total_nights }} malam</span>
                <span>Rp {{ number_format($booking->total_price, 0, ',', '.') }}</span>
            </div>
            @if($booking->discount_amount > 0)
            <div class="flex justify-between text-green-600">
                <span>Diskon</span>
                <span>- Rp {{ number_format($booking->discount_amount, 0, ',', '.') }}</span>
            </div>
            @endif
            <div class="flex justify-between font-bold text-gray-800 text-base pt-2 border-t">
                <span>Total Bayar</span>
                <span>Rp {{ number_format($booking->final_price, 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    {{-- Pembayaran --}}
    @if($booking->payment)
    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
        <h2 class="font-semibold text-gray-700 mb-4">Pembayaran</h2>
        <div class="grid grid-cols-2 gap-4 text-sm">
            <div>
                <p class="text-gray-400">Kode Pembayaran</p>
                <p class="font-mono text-xs text-gray-700">{{ $booking->payment->payment_code }}</p>
            </div>
            <div>
                <p class="text-gray-400">Status</p>
                <p class="font-medium {{ $booking->payment->isPaid() ? 'text-green-600' : 'text-yellow-600' }}">
                    {{ ucfirst($booking->payment->status) }}
                </p>
            </div>
            <div>
                <p class="text-gray-400">Total</p>
                <p class="font-medium text-gray-700">Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</p>
            </div>
            @if($booking->payment->paid_at)
            <div>
                <p class="text-gray-400">Dibayar</p>
                <p class="font-medium text-gray-700">{{ $booking->payment->paid_at->format('d M Y H:i') }}</p>
            </div>
            @endif
        </div>

        @if($booking->status === 'pending' && !$booking->payment->isPaid())
        <div class="mt-4 pt-4 border-t">
            <p class="text-sm text-gray-500 mb-3">Lakukan transfer ke rekening berikut:</p>
            <div class="bg-gray-50 rounded-lg p-4 text-sm">
                <p class="text-gray-600">Bank BCA</p>
                <p class="font-bold text-gray-800 text-lg">1234567890</p>
                <p class="text-gray-600">a.n. {{ config('app.name') }}</p>
                <p class="text-gray-500 mt-2">Nominal: <strong>Rp {{ number_format($booking->payment->amount, 0, ',', '.') }}</strong></p>
            </div>
        </div>
        @endif
    </div>
    @endif

</div>
@endsection