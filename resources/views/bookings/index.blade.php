@extends('layouts.app')

@section('title', 'Riwayat Booking')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Riwayat Booking</h1>
        <p class="text-gray-500 text-sm mt-1">Semua booking yang pernah kamu buat</p>
    </div>

    @if($bookings->isEmpty())
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm px-6 py-16 text-center">
            <p class="text-gray-400 text-sm">Belum ada booking.</p>
            <a href="{{ route('properties.index') }}"
               class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
                Cari Penginapan
            </a>
        </div>
    @else
        <div class="space-y-4">
            @foreach($bookings as $booking)
            @php
                $colors = [
                    'pending'     => 'bg-yellow-100 text-yellow-700',
                    'confirmed'   => 'bg-blue-100 text-blue-700',
                    'checked_in'  => 'bg-green-100 text-green-700',
                    'checked_out' => 'bg-gray-100 text-gray-600',
                    'cancelled'   => 'bg-red-100 text-red-600',
                ];
            @endphp
            <a href="{{ route('bookings.show', $booking) }}"
               class="block bg-white rounded-xl border border-gray-100 shadow-sm p-5 hover:shadow-md transition">
                <div class="flex items-start justify-between flex-wrap gap-3">
                    <div>
                        <p class="font-semibold text-gray-800">{{ $booking->room->property->name }}</p>
                        <p class="text-sm text-gray-500 mt-1">{{ $booking->room->name }}</p>
                        <p class="text-xs text-gray-400 mt-1 font-mono">{{ $booking->booking_code }}</p>
                    </div>
                    <span class="px-3 py-1 rounded-full text-xs font-medium {{ $colors[$booking->status] ?? '' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>

                <div class="mt-4 flex flex-wrap gap-6 text-sm text-gray-500">
                    <div>
                        <p class="text-gray-400 text-xs">Check-in</p>
                        <p class="font-medium text-gray-700">{{ $booking->check_in->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Check-out</p>
                        <p class="font-medium text-gray-700">{{ $booking->check_out->format('d M Y') }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400 text-xs">Total</p>
                        <p class="font-medium text-indigo-600">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-6">
            {{ $bookings->links() }}
        </div>
    @endif

</div>
@endsection