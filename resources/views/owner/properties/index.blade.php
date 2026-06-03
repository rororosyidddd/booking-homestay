@extends('layouts.owner')

@section('title', 'Properti Saya')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Properti Saya</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola semua properti Anda</p>
    </div>
    <a href="{{ route('owner.properties.create') }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Tambah Properti
    </a>
</div>

@if($properties->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-16 text-center">
        <p class="text-gray-400 text-sm">Belum ada properti. Tambahkan properti pertama Anda!</p>
        <a href="{{ route('owner.properties.create') }}" class="mt-4 inline-block bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-indigo-700">
            Tambah Properti
        </a>
    </div>
@else
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-3 gap-5">
        @foreach($properties as $property)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between">
                <div>
                    <h3 class="font-semibold text-gray-800">{{ $property->name }}</h3>
                    <p class="text-sm text-gray-500 mt-1">{{ $property->city }}, {{ $property->province }}</p>
                </div>
                @php
                    $statusColors = [
                        'active'   => 'bg-green-100 text-green-700',
                        'inactive' => 'bg-gray-100 text-gray-500',
                        'pending'  => 'bg-yellow-100 text-yellow-700',
                    ];
                @endphp
                <span class="text-xs px-2 py-1 rounded-full font-medium {{ $statusColors[$property->status] ?? '' }}">
                    {{ ucfirst($property->status) }}
                </span>
            </div>

            <div class="mt-4 flex items-center gap-4 text-sm text-gray-500">
                <span>{{ $property->type }}</span>
                <span>•</span>
                <span>{{ $property->rooms_count }} kamar</span>
            </div>

            <div class="mt-4 flex gap-2">
                <a href="{{ route('owner.properties.show', $property) }}"
                   class="flex-1 text-center text-sm bg-indigo-50 text-indigo-600 px-3 py-2 rounded-lg hover:bg-indigo-100">
                    Detail
                </a>
                <a href="{{ route('owner.properties.rooms.index', $property) }}"
                   class="flex-1 text-center text-sm bg-gray-50 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-100">
                    Kamar
                </a>
                <a href="{{ route('owner.properties.edit', $property) }}"
                   class="flex-1 text-center text-sm bg-gray-50 text-gray-600 px-3 py-2 rounded-lg hover:bg-gray-100">
                    Edit
                </a>
            </div>
        </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $properties->links() }}
    </div>
@endif
@endsection
