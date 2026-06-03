@extends('layouts.admin')

@section('title', 'Manajemen Booking')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Booking</h2>
    <p class="text-gray-500 text-sm mt-1">Semua booking dari seluruh properti</p>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari kode / nama tamu..."
           class="flex-1 min-w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <option value="">Semua Status</option>
        @foreach(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $s)) }}
            </option>
        @endforeach
    </select>
    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Filter</button>
    <a href="{{ route('admin.bookings.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Reset</a>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Kode</th>
                    <th class="px-6 py-3 text-left">Tamu</th>
                    <th class="px-6 py-3 text-left">Properti</th>
                    <th class="px-6 py-3 text-left">Check-in</th>
                    <th class="px-6 py-3 text-left">Total</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
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
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-mono text-xs text-gray-600">{{ $booking->booking_code }}</td>
                    <td class="px-6 py-4">
                        <p class="font-medium text-gray-700">{{ $booking->guest_name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->guest_phone }}</p>
                    </td>
                    <td class="px-6 py-4 text-gray-600">{{ $booking->room->property->name }}</td>
                    <td class="px-6 py-4 text-gray-600">{{ $booking->check_in->format('d M Y') }}</td>
                    <td class="px-6 py-4 font-medium text-gray-700">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$booking->status] ?? '' }}">
                            {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('admin.bookings.show', $booking) }}"
                           class="text-xs text-red-600 hover:underline">Detail</a>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $bookings->links() }}
    </div>
</div>
@endsection