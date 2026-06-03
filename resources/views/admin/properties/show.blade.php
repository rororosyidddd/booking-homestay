@extends('layouts.admin')

@section('title', 'Detail Properti')

@section('content')
<div class="mb-6">
    <a href="{{ route('admin.properties.index') }}" class="text-sm text-red-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ $property->name }}</h2>
    <p class="text-gray-500 text-sm">{{ $property->address }}, {{ $property->city }}</p>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- Info Properti --}}
    <div class="space-y-5">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-4">Informasi</h3>
            <div class="space-y-3 text-sm">
                <div>
                    <p class="text-gray-400">Owner</p>
                    <a href="{{ route('admin.users.show', $property->owner) }}"
                       class="text-red-600 hover:underline">{{ $property->owner->name }}</a>
                </div>
                <div>
                    <p class="text-gray-400">Tipe</p>
                    <p class="text-gray-700">{{ ucfirst(str_replace('_', ' ', $property->type)) }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Kota</p>
                    <p class="text-gray-700">{{ $property->city }}, {{ $property->province }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Check-in / Check-out</p>
                    <p class="text-gray-700">{{ $property->check_in_time }} / {{ $property->check_out_time }}</p>
                </div>
                <div>
                    <p class="text-gray-400">Status</p>
                    @php
                        $statusColors = [
                            'active'   => 'bg-green-100 text-green-700',
                            'inactive' => 'bg-gray-100 text-gray-500',
                            'pending'  => 'bg-yellow-100 text-yellow-700',
                        ];
                    @endphp
                    <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$property->status] ?? '' }}">
                        {{ ucfirst($property->status) }}
                    </span>
                </div>
            </div>
        </div>

        {{-- Aksi --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <h3 class="font-semibold text-gray-700 mb-3">Aksi</h3>
            <div class="space-y-2">
                @if($property->status === 'pending')
                <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-green-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-green-700">
                        Approve Properti
                    </button>
                </form>
                <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-yellow-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-yellow-600">
                        Tolak Properti
                    </button>
                </form>
                @endif
                @if($property->status === 'active')
                <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                    @csrf @method('PATCH')
                    <button type="submit" class="w-full bg-gray-500 text-white px-4 py-2 rounded-lg text-sm hover:bg-gray-600">
                        Nonaktifkan
                    </button>
                </form>
                @endif
                <form method="POST" action="{{ route('admin.properties.destroy', $property) }}"
                      onsubmit="return confirm('Hapus properti ini?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="w-full bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">
                        Hapus Properti
                    </button>
                </form>
            </div>
        </div>
    </div>

    {{-- Kamar & Review --}}
    <div class="lg:col-span-2 space-y-5">

        {{-- Kamar --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-700">Kamar ({{ $property->rooms->count() }})</h3>
            </div>
            @if($property->rooms->isEmpty())
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada kamar.</div>
            @else
                <div class="divide-y">
                    @foreach($property->rooms as $room)
                    <div class="px-6 py-4 flex items-center justify-between">
                        <div>
                            <p class="text-sm font-medium text-gray-700">{{ $room->name }}</p>
                            <p class="text-xs text-gray-400">{{ $room->capacity }} orang · {{ $room->bed_type ?? '-' }}</p>
                        </div>
                        <p class="text-sm font-semibold text-indigo-600">
                            Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                        </p>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        {{-- Review --}}
        <div class="bg-white rounded-xl shadow-sm border border-gray-100">
            <div class="px-6 py-4 border-b">
                <h3 class="font-semibold text-gray-700">Ulasan ({{ $property->reviews->count() }})</h3>
            </div>
            @if($property->reviews->isEmpty())
                <div class="px-6 py-8 text-center text-gray-400 text-sm">Belum ada ulasan.</div>
            @else
                <div class="divide-y">
                    @foreach($property->reviews as $review)
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between mb-1">
                            <p class="text-sm font-medium text-gray-700">{{ $review->user->name }}</p>
                            <div class="flex text-yellow-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                        <p class="text-xs text-gray-500">{{ $review->comment }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

    </div>
</div>
@endsection