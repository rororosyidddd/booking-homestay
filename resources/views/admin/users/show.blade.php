@extends('layouts.admin')

@section('title', 'Detail User')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.users.index') }}" class="text-sm text-red-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Detail User</h2>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Info User --}}
    <div class="space-y-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center gap-4 mb-4">
                <div class="w-12 h-12 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold text-lg">
                    {{ strtoupper(substr($user->name, 0, 1)) }}
                </div>
                <div>
                    <p class="font-semibold text-gray-800">{{ $user->name }}</p>
                    <p class="text-sm text-gray-500">{{ $user->email }}</p>
                </div>
            </div>

            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-400">No. HP</p>
                    <p class="text-gray-700">{{ $user->phone ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Terdaftar</p>
                    <p class="text-gray-700">{{ $user->created_at->format('d M Y') }}</p>
                </div>
            </div>
        </div>

        {{-- Ubah Role --}}
        @if($user->id !== auth()->id())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Ubah Role</h3>
            <form method="POST" action="{{ route('admin.users.role', $user) }}">
                @csrf @method('PATCH')
                <select name="role" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500 mb-3">
                    <option value="guest" {{ $user->role === 'guest' ? 'selected' : '' }}>Guest</option>
                    <option value="owner" {{ $user->role === 'owner' ? 'selected' : '' }}>Owner</option>
                    <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
                <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                    Simpan Role
                </button>
            </form>
        </div>
        @endif
    </div>

    {{-- Properti & Booking --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Properti --}}
        @if($user->properties->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-700">Properti ({{ $user->properties->count() }})</h3>
            </div>
            <div class="divide-y">
                @foreach($user->properties as $property)
                <div class="px-6 py-4 flex items-center justify-between">
                    <div>
                        <p class="text-sm font-medium text-gray-700">{{ $property->name }}</p>
                        <p class="text-xs text-gray-400">{{ $property->city }} · {{ ucfirst($property->type) }}</p>
                    </div>
                    @php
                        $statusColors = [
                            'active'   => 'bg-green-100 text-green-700',
                            'inactive' => 'bg-gray-100 text-gray-500',
                            'pending'  => 'bg-yellow-100 text-yellow-700',
                        ];
                    @endphp
                    <span class="text-xs px-2 py-1 rounded-full {{ $statusColors[$property->status] ?? '' }}">
                        {{ ucfirst($property->status) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Booking --}}
        @if($user->bookings->isNotEmpty())
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-700">Riwayat Booking ({{ $user->bookings->count() }})</h3>
            </div>
            <div class="divide-y">
                @foreach($user->bookings->take(5) as $booking)
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
                        <p class="text-sm font-medium text-gray-700">{{ $booking->room->property->name }}</p>
                        <p class="text-xs text-gray-400">{{ $booking->check_in->format('d M Y') }} · {{ $booking->total_nights }} malam</p>
                    </div>
                    <span class="text-xs px-2 py-1 rounded-full {{ $colors[$booking->status] ?? '' }}">
                        {{ ucfirst(str_replace('_', ' ', $booking->status)) }}
                    </span>
                </div>
                @endforeach
            </div>
        </div>
        @endif

    </div>
</div>
@endsection