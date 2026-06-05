@extends('layouts.app')
@use('Illuminate\Support\Facades\Storage')
@section('title', 'Beranda')

@section('content')

{{-- Hero --}}
<div class="bg-indigo-600 text-white py-16">
    <div class="max-w-6xl mx-auto px-4 text-center">
        <h1 class="text-4xl font-bold mb-4">Temukan Penginapan Terbaik</h1>
        <p class="text-indigo-200 mb-8">Hotel, villa, guest house, dan kost di seluruh Indonesia</p>

        <form action="{{ route('properties.index') }}" method="GET"
              class="bg-white rounded-xl p-4 flex flex-wrap gap-3 max-w-3xl mx-auto">
            <input type="text" name="city" placeholder="Kota tujuan..."
                   value="{{ request('city') }}"
                   class="flex-1 min-w-40 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <input type="date" name="check_in" value="{{ request('check_in') }}"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <input type="date" name="check_out" value="{{ request('check_out') }}"
                   class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                Cari
            </button>
        </form>
    </div>
</div>

{{-- Properti Populer --}}
<div class="max-w-6xl mx-auto px-4 py-12">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Properti Populer</h2>

    @if($properties->isEmpty())
        <p class="text-gray-400 text-sm">Belum ada properti tersedia.</p>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
            <a href="{{ route('properties.show', $property) }}"
               class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                @if($property->cover_image)
                    <img src="{{ Storage::url($property->cover_image) }}" alt="{{ $property->name }}"
                        class="w-full h-48 object-cover">
                @else
                    <div class="h-48 bg-indigo-100 flex items-center justify-center">
                        <span class="text-indigo-300 text-4xl">🏨</span>
                    </div>
                @endif
                <div class="p-4">
                    <div class="flex items-start justify-between">
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $property->name }}</h3>
                            <p class="text-sm text-gray-500 mt-1">{{ $property->city }}, {{ $property->province }}</p>
                        </div>
                        <span class="text-xs bg-indigo-50 text-indigo-600 px-2 py-1 rounded-full">
                            {{ ucfirst(str_replace('_', ' ', $property->type)) }}
                        </span>
                    </div>
                    <div class="mt-3 flex items-center justify-between">
                        <p class="text-sm text-gray-500">{{ $property->rooms_count }} tipe kamar</p>
                        <p class="text-indigo-600 font-semibold text-sm">
                            Mulai Rp {{ number_format($property->rooms->min('price_per_night'), 0, ',', '.') }}
                        </p>
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    @endif
</div>

@endsection