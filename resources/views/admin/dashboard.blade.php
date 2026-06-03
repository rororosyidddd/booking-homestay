@extends('layouts.admin')

@section('title', 'Admin Dashboard')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Dashboard Admin</h2>
    <p class="text-gray-500 text-sm mt-1">Overview seluruh sistem</p>
</div>

{{-- Stats --}}
<div class="grid grid-cols-2 lg:grid-cols-4 gap-5 mb-8">
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total User</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_users'] }}</p>
        <p class="text-xs text-gray-400 mt-1">{{ $stats['total_owners'] }} owner · {{ $stats['total_guests'] }} guest</p>
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Properti</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_properties'] }}</p>
        @if($stats['pending_properties'] > 0)
        <p class="text-xs text-yellow-500 mt-1">{{ $stats['pending_properties'] }} menunggu approval</p>
        @endif
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Booking</p>
        <p class="text-3xl font-bold text-gray-800 mt-1">{{ $stats['total_bookings'] }}</p>
        @if($stats['pending_bookings'] > 0)
        <p class="text-xs text-yellow-500 mt-1">{{ $stats['pending_bookings'] }} pending</p>
        @endif
    </div>
    <div class="bg-white rounded-xl p-5 shadow-sm border border-gray-100">
        <p class="text-sm text-gray-500">Total Revenue</p>
        <p class="text-2xl font-bold text-green-600 mt-1">Rp {{ number_format($stats['total_revenue'], 0, ',', '.') }}</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

    {{-- Properti Pending --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700">Properti Menunggu Approval</h3>
            <a href="{{ route('admin.properties.index', ['status' => 'pending']) }}" class="text-sm text-red-600 hover:underline">Lihat semua</a>
        </div>
        @if($pendingProperties->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400 text-sm">Tidak ada properti pending.</div>
        @else
            <div class="divide-y">
                @foreach($pendingProperties as $property)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-700 text-sm">{{ $property->name }}</p>
                        <p class="text-xs text-gray-400">{{ $property->city }} · {{ $property->owner->name }}</p>
                    </div>
                    <div class="flex gap-2">
                        <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs bg-green-100 text-green-700 px-3 py-1 rounded-full hover:bg-green-200">
                                Approve
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                            @csrf @method('PATCH')
                            <button type="submit" class="text-xs bg-red-100 text-red-600 px-3 py-1 rounded-full hover:bg-red-200">
                                Reject
                            </button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- Booking Terbaru --}}
    <div class="bg-white rounded-xl shadow-sm border border-gray-100">
        <div class="flex items-center justify-between px-6 py-4 border-b">
            <h3 class="font-semibold text-gray-700">Booking Terbaru</h3>
            <a href="{{ route('admin.bookings.index') }}" class="text-sm text-red-600 hover:underline">Lihat semua</a>
        </div>
        @if($recentBookings->isEmpty())
            <div class="px-6 py-10 text-center text-gray-400 text-sm">Belum ada booking.</div>
        @else
            <div class="divide-y">
                @foreach($recentBookings as $booking)
                @php
                    $colors = [
                        'pending'     => 'bg-yellow-100 text-yellow-700',
                        'confirmed'   => 'bg-blue-100 text-blue-700',
                        'checked_in'  => 'bg-green-100 text-green-700',
                        'checked_out' => 'bg-gray-100 text-gray-600',
                        'cancelled'   => 'bg-red-100 text-red-600',
                    ];
                @endphp
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="font-medium text-gray-700 text-sm">{{ $booking->guest_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->room->property->name }} · {{ $booking->booking_code }}</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $colors[$booking->status] ?? '' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
                @endforeach
            </div>
        @endif
    </div>

</div>
@endsection