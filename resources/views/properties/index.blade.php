@extends('layouts.app')

@section('title', 'Cari Properti')

@section('content')

{{-- Search Bar --}}
<div class="bg-indigo-600 text-white py-10">
    <div class="max-w-6xl mx-auto px-4">
        <h1 class="text-2xl font-bold mb-6">Cari Penginapan</h1>
        <form action="{{ route('properties.index') }}" method="GET"
              class="bg-white rounded-xl p-4 flex flex-wrap gap-3">
            <input type="text" name="city" placeholder="Kota tujuan..."
                   value="{{ request('city') }}"
                   class="flex-1 min-w-40 border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
            <select name="type" class="border border-gray-200 rounded-lg px-3 py-2 text-sm text-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <option value="">Semua Tipe</option>
                @foreach(['hotel', 'villa', 'guest_house', 'kost', 'resort', 'apartment'] as $type)
                    <option value="{{ $type }}" {{ request('type') === $type ? 'selected' : '' }}>
                        {{ ucfirst(str_replace('_', ' ', $type)) }}
                    </option>
                @endforeach
            </select>
            <button type="submit"
                    class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                Cari
            </button>
            @if(request()->hasAny(['city', 'type']))
                <a href="{{ route('properties.index') }}"
                   class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">
                    Reset
                </a>
            @endif
        </form>
    </div>
</div>

{{-- Results --}}
<div class="max-w-6xl mx-auto px-4 py-10">

    <p class="text-sm text-gray-500 mb-6">
        Menampilkan {{ $properties->total() }} properti
        @if(request('city')) di <strong>{{ request('city') }}</strong> @endif
    </p>

    @if($properties->isEmpty())
        <div class="text-center py-16">
            <p class="text-gray-400">Tidak ada properti yang ditemukan.</p>
            <a href="{{ route('properties.index') }}" class="mt-4 inline-block text-indigo-600 hover:underline text-sm">Lihat semua properti</a>
        </div>
    @else
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            @foreach($properties as $property)
            <a href="{{ route('properties.show', $property) }}"
               class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition">
                <div class="h-48 bg-indigo-100 flex items-center justify-center">
                    <span class="text-indigo-300 text-4xl">🏨</span>
                </div>
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
                        @if($property->rooms->isNotEmpty())
                        <p class="text-indigo-600 font-semibold text-sm">
                            Mulai Rp {{ number_format($property->rooms->min('price_per_night'), 0, ',', '.') }}
                        </p>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        <div class="mt-8">
            {{ $properties->links() }}
        </div>
    @endif
</div>

@endsection