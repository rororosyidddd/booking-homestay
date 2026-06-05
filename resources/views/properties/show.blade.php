@extends('layouts.app')

@section('title', $property->name)

@section('content')

<div class="max-w-6xl mx-auto px-4 py-10">

    {{-- Header --}}
    <div class="mb-6">
        <a href="{{ route('properties.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
        <h1 class="text-3xl font-bold text-gray-800 mt-2">{{ $property->name }}</h1>
        <p class="text-gray-500 mt-1">{{ $property->address }}, {{ $property->city }}, {{ $property->province }}</p>
        <div class="flex items-center gap-3 mt-2">
            <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full">
                {{ ucfirst(str_replace('_', ' ', $property->type)) }}
            </span>
            <span class="text-sm text-gray-500">Check-in: {{ $property->check_in_time }}</span>
            <span class="text-sm text-gray-500">Check-out: {{ $property->check_out_time }}</span>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Kiri: Info + Kamar --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Deskripsi --}}
            @if($property->description)
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h2 class="font-semibold text-gray-700 mb-2">Tentang Properti</h2>
                <p class="text-sm text-gray-600 leading-relaxed">{{ $property->description }}</p>
            </div>
            @endif

            {{-- Daftar Kamar --}}
            <div>
                <h2 class="font-semibold text-gray-700 mb-4 text-lg">Pilih Kamar</h2>
                <div class="space-y-4">
                    @foreach($property->rooms as $room)
                    @if($room->status === 'available')
                    <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
                     @if($room->images->isNotEmpty())
                    <img src="{{ Storage::url($room->images->where('is_primary', true)->first()?->path ?? $room->images->first()->path) }}"
                    alt="{{ $room->name }}"
                    class="w-full h-48 object-cover">
                     @else
        <div class="h-48 bg-indigo-100 flex items-center justify-center">
            <span class="text-indigo-300 text-4xl">🏨</span>
        </div>
    @endif
    <div class="p-5">
        <div class="flex items-start justify-between flex-wrap gap-4">
                        <div class="flex items-start justify-between flex-wrap gap-4">
                            <div class="flex-1">
                                <h3 class="font-semibold text-gray-800">{{ $room->name }}</h3>
                                <p class="text-sm text-gray-500 mt-1">{{ $room->description }}</p>

                                <div class="flex flex-wrap gap-3 mt-3 text-sm text-gray-500">
                                    @if($room->bed_type)
                                        <span>🛏 {{ $room->bed_type }}</span>
                                    @endif
                                    @if($room->size_sqm)
                                        <span>📐 {{ $room->size_sqm }} m²</span>
                                    @endif
                                    <span>👤 Max {{ $room->capacity }} orang</span>
                                </div>

                                @if($room->facilities->isNotEmpty())
                                <div class="flex flex-wrap gap-2 mt-3">
                                    @foreach($room->facilities as $facility)
                                        <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">
                                            {{ $facility->name }}
                                        </span>
                                    @endforeach
                                </div>
                                @endif
                            </div>

                            <div class="text-right">
                                <p class="text-2xl font-bold text-indigo-600">
                                    Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                                </p>
                                <p class="text-xs text-gray-400">per malam</p>
                                @if($room->weekend_price)
                                <p class="text-xs text-gray-400 mt-1">
                                    Weekend: Rp {{ number_format($room->weekend_price, 0, ',', '.') }}
                                </p>
                                @endif
                                <a href="{{ route('bookings.create', ['room' => $room->id]) }}"
                                   class="mt-3 inline-block bg-indigo-600 text-white px-5 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                                    Pesan Sekarang
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                </div>
            </div>
        </div>

        {{-- Kanan: Info Kontak --}}
        <div class="space-y-5">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4">Informasi Kontak</h3>
                <div class="space-y-3 text-sm">
                    @if($property->phone)
                    <div class="flex gap-2 text-gray-600">
                        <span>📞</span>
                        <span>{{ $property->phone }}</span>
                    </div>
                    @endif
                    @if($property->email)
                    <div class="flex gap-2 text-gray-600">
                        <span>✉️</span>
                        <span>{{ $property->email }}</span>
                    </div>
                    @endif
                    <div class="flex gap-2 text-gray-600">
                        <span>📍</span>
                        <span>{{ $property->address }}</span>
                    </div>
                </div>
            </div>

            {{-- Reviews --}}
            @if($property->reviews->isNotEmpty())
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5">
                <h3 class="font-semibold text-gray-700 mb-4">
                    Ulasan
                    <span class="text-indigo-600">({{ $property->reviews->count() }})</span>
                </h3>
                <div class="space-y-4">
                    @foreach($property->reviews->take(3) as $review)
                    <div class="border-b pb-3 last:border-0">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium text-gray-700">{{ $review->user->name }}</p>
                            <div class="flex text-yellow-400 text-xs">
                                @for($i = 1; $i <= 5; $i++)
                                    {{ $i <= $review->rating ? '★' : '☆' }}
                                @endfor
                            </div>
                        </div>
                        @if($review->comment)
                        <p class="text-xs text-gray-500 mt-1">{{ $review->comment }}</p>
                        @endif
                    </div>
                    @endforeach
                </div>
            </div>
            @endif
        </div>

    </div>
</div>

@endsection