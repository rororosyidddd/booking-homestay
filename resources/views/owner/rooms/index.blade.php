@extends('layouts.owner')

@section('title', 'Kamar')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <a href="{{ route('owner.properties.show', $property) }}" class="text-sm text-indigo-600 hover:underline">← {{ $property->name }}</a>
        <h2 class="text-2xl font-bold text-gray-800 mt-1">Kamar</h2>
    </div>
    <a href="{{ route('owner.properties.rooms.create', $property) }}"
       class="bg-indigo-600 text-white px-4 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
        + Tambah Kamar
    </a>
</div>

@if($rooms->isEmpty())
    <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-6 py-16 text-center">
        <p class="text-gray-400 text-sm">Belum ada kamar.</p>
    </div>
@else
    <div class="space-y-5">
        @foreach($rooms as $room)
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-start justify-between flex-wrap gap-4">
                <div class="flex-1">
                    <div class="flex items-center gap-3">
                        <h3 class="font-semibold text-gray-800">{{ $room->name }}</h3>
                        <span class="text-xs px-2 py-1 rounded-full {{ $room->status === 'available' ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-500' }}">
                            {{ ucfirst($room->status) }}
                        </span>
                    </div>
                    <div class="flex flex-wrap gap-4 mt-2 text-sm text-gray-500">
                        <span>👤 {{ $room->capacity }} orang</span>
                        @if($room->bed_type) <span>🛏 {{ $room->bed_type }}</span> @endif
                        @if($room->size_sqm) <span>📐 {{ $room->size_sqm }} m²</span> @endif
                        <span>🏠 {{ $room->total_rooms }} unit</span>
                    </div>
                    @if($room->facilities->isNotEmpty())
                    <div class="flex flex-wrap gap-1 mt-2">
                        @foreach($room->facilities as $facility)
                            <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $facility->name }}</span>
                        @endforeach
                    </div>
                    @endif
                </div>

                <div class="text-right">
                    <p class="text-xl font-bold text-indigo-600">Rp {{ number_format($room->price_per_night, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-400">per malam</p>
                    <div class="flex gap-2 mt-3">
                        <a href="{{ route('owner.properties.rooms.edit', [$property, $room]) }}"
                           class="text-sm bg-indigo-50 text-indigo-600 px-3 py-1 rounded-lg hover:bg-indigo-100">Edit</a>
                        <form method="POST" action="{{ route('owner.properties.rooms.destroy', [$property, $room]) }}"
                              onsubmit="return confirm('Hapus kamar {{ $room->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-sm bg-red-50 text-red-600 px-3 py-1 rounded-lg hover:bg-red-100">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Foto --}}
            <div class="mt-4 pt-4 border-t">
                <p class="text-sm font-medium text-gray-600 mb-3">Foto Kamar</p>
                <div class="flex flex-wrap gap-3">
                    @foreach($room->images as $image)
                    <div class="relative group">
                        <img src="{{ Storage::url($image->path) }}" alt=""
                             class="w-24 h-24 object-cover rounded-lg {{ $image->is_primary ? 'ring-2 ring-indigo-500' : '' }}">
                        @if($image->is_primary)
                            <span class="absolute top-1 left-1 text-xs bg-indigo-600 text-white px-1 rounded">Utama</span>
                        @endif
                        <div class="absolute inset-0 bg-black bg-opacity-40 rounded-lg opacity-0 group-hover:opacity-100 flex items-center justify-center gap-1 transition">
                            @if(!$image->is_primary)
                            <form method="POST" action="{{ route('owner.rooms.images.primary', $image) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs bg-white text-gray-700 px-2 py-1 rounded">Utama</button>
                            </form>
                            @endif
                            <form method="POST" action="{{ route('owner.rooms.images.destroy', $image) }}"
                                  onsubmit="return confirm('Hapus foto ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs bg-red-600 text-white px-2 py-1 rounded">Hapus</button>
                            </form>
                        </div>
                    </div>
                    @endforeach

                    {{-- Upload --}}
                    <form method="POST" action="{{ route('owner.rooms.images.store') }}"
                          enctype="multipart/form-data"
                          class="w-24 h-24 border-2 border-dashed border-gray-300 rounded-lg flex items-center justify-center cursor-pointer hover:border-indigo-400">
                        @csrf
                        <input type="hidden" name="room_id" value="{{ $room->id }}">
                        <label class="cursor-pointer text-center">
                            <span class="text-2xl text-gray-300">+</span>
                            <input type="file" name="images[]" multiple accept="image/*" class="hidden"
                                   onchange="this.closest('form').submit()">
                        </label>
                    </form>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endif
@endsection