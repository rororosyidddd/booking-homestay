@extends('layouts.owner')

@section('title', 'Edit Kamar')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.properties.rooms.index', $property) }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Edit Kamar</h2>
    <p class="text-gray-500 text-sm">{{ $property->name }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ route('owner.properties.rooms.update', [$property, $room]) }}" enctype="multipart/form-data">
        @csrf @method('PUT')
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kamar <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $room->name) }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $room->description) }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Malam <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_night" value="{{ old('price_per_night', $room->price_per_night) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Weekend</label>
                    <input type="number" name="weekend_price" value="{{ old('weekend_price', $room->weekend_price) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" value="{{ old('capacity', $room->capacity) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Unit <span class="text-red-500">*</span></label>
                    <input type="number" name="total_rooms" value="{{ old('total_rooms', $room->total_rooms) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kasur</label>
                    <input type="text" name="bed_type" value="{{ old('bed_type', $room->bed_type) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Luas (m²)</label>
                    <input type="number" name="size_sqm" value="{{ old('size_sqm', $room->size_sqm) }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Status</label>
                <select name="status" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="available" {{ $room->status === 'available' ? 'selected' : '' }}>Available</option>
                    <option value="unavailable" {{ $room->status === 'unavailable' ? 'selected' : '' }}>Unavailable</option>
                </select>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                <p class="text-xs text-gray-400 mb-2">Pisahkan dengan koma</p>
                <input type="text" id="facilities-input"
                       value="{{ $room->facilities->pluck('name')->join(', ') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div id="facilities-container">
                    @foreach($room->facilities as $facility)
                        <input type="hidden" name="facilities[]" value="{{ $facility->name }}">
                    @endforeach
                </div>
            </div>

        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                Update Kamar
            </button>
            <a href="{{ route('owner.properties.rooms.index', $property) }}"
               class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>

<script>
document.getElementById('facilities-input').addEventListener('blur', function() {
    const container = document.getElementById('facilities-container');
    container.innerHTML = '';
    this.value.split(',').forEach(function(item) {
        const val = item.trim();
        if (val) {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'facilities[]';
            input.value = val;
            container.appendChild(input);
        }
    });
});
</script>
@endsection