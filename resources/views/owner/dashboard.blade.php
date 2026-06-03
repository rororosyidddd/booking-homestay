@extends('layouts.owner')

@section('title', 'Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard</h2>
    <p class="text-gray-500 text-sm mt-1">Selamat datang, {{ auth()->user()->name }}</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Properti</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_properties'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Booking</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_bookings'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Booking Pending</p>
        <p class="text-3xl font-bold text-yellow-500 mt-1">{{ $stats['pending_bookings'] }}</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Pendapatan</p>
        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
    </div>
</div>

{{-- Recent Bookings --}}
<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="flex items-center justify-between px-6 py-4 border-b">
        <h3 class="font-semibold text-gray-700">Booking Terbaru</h3>
        <a href="{{ route('owner.bookings.index') }}" class="text-sm text-indigo-600 hover:underline">Lihat semua</a>
    </div>

    @if($recentBookings->isEmpty())
        <div class="px-6 py-10 text-center text-gray-400 text-sm">Belum ada booking masuk.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Tamu</th>
                        <th class="px-6 py-3 text-left">Kamar</th>
                        <th class="px-6 py-3 text-left">Check-in</th>
                        <th class="px-6 py-3 text-left">Total</th>
                        <th class="px-6 py-3 text-left">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @foreach($recentBookings as $booking)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $booking->booking_code }}</td>
                        <td class="px-6 py-4 text-gray-700">{{ $booking->guest_name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $booking->room->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $booking->check_in->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-700 font-medium">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $colors = [
                                    'pending'     => 'bg-yellow-100 text-yellow-700',
                                    'confirmed'   => 'bg-blue-100 text-blue-700',
                                    'checked_in'  => 'bg-green-100 text-green-700',
                                    'checked_out' => 'bg-gray-100 text-gray-600',
                                    'cancelled'   => 'bg-red-100 text-red-600',
                                ];
                            @endphp
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$booking->status] ?? 'bg-gray-100 text-gray-600' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
@endsection
