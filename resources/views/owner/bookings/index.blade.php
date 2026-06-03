@extends('layouts.owner')

@section('title', 'Booking Masuk')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Booking Masuk</h2>
    <p class="text-gray-500 text-sm mt-1">Kelola semua booking properti Anda</p>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5 flex gap-3 flex-wrap">
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
        <option value="">Semua Status</option>
        @foreach(['pending', 'confirmed', 'checked_in', 'checked_out', 'cancelled'] as $s)
            <option value="{{ $s }}" {{ request('status') === $s ? 'selected' : '' }}>
                {{ ucfirst(str_replace('_', ' ', $s)) }}
            </option>
        @endforeach
    </select>
    <input type="date" name="date" value="{{ request('date') }}"
           class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">Filter</button>
    <a href="{{ route('owner.bookings.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Reset</a>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    @if($bookings->isEmpty())
        <div class="px-6 py-16 text-center text-gray-400 text-sm">Belum ada booking.</div>
    @else
        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                    <tr>
                        <th class="px-6 py-3 text-left">Kode</th>
                        <th class="px-6 py-3 text-left">Tamu</th>
                        <th class="px-6 py-3 text-left">Kamar</th>
                        <th class="px-6 py-3 text-left">Check-in</th>
                        <th class="px-6 py-3 text-left">Check-out</th>
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
                        <td class="px-6 py-4 text-gray-600">{{ $booking->room->name }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $booking->check_in->format('d M Y') }}</td>
                        <td class="px-6 py-4 text-gray-600">{{ $booking->check_out->format('d M Y') }}</td>
                        <td class="px-6 py-4 font-medium text-gray-700">Rp {{ number_format($booking->final_price, 0, ',', '.') }}</td>
                        <td class="px-6 py-4">
                            <span class="px-2 py-1 rounded-full text-xs font-medium {{ $colors[$booking->status] ?? '' }}">
                                {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('owner.bookings.show', $booking) }}" class="text-indigo-600 hover:underline text-xs">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div class="px-6 py-4 border-t">
            {{ $bookings->links() }}
        </div>
    @endif
</div>
@endsection
