@extends('layouts.owner')

@section('title', 'Tambah Kamar')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.properties.rooms.index', $property) }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">Tambah Kamar</h2>
    <p class="text-gray-500 text-sm">{{ $property->name }}</p>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ route('owner.properties.rooms.store', $property) }}" enctype="multipart/form-data">
        @csrf
        <div class="space-y-5">

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Kamar <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga per Malam <span class="text-red-500">*</span></label>
                    <input type="number" name="price_per_night" value="{{ old('price_per_night') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('price_per_night') border-red-400 @enderror">
                    @error('price_per_night') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Harga Weekend</label>
                    <input type="number" name="weekend_price" value="{{ old('weekend_price') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kapasitas <span class="text-red-500">*</span></label>
                    <input type="number" name="capacity" value="{{ old('capacity', 2) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Unit <span class="text-red-500">*</span></label>
                    <input type="number" name="total_rooms" value="{{ old('total_rooms', 1) }}" min="1"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Tipe Kasur</label>
                    <input type="text" name="bed_type" value="{{ old('bed_type') }}" placeholder="King, Queen, Twin..."
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Luas (m²)</label>
                    <input type="number" name="size_sqm" value="{{ old('size_sqm') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Fasilitas</label>
                <p class="text-xs text-gray-400 mb-2">Pisahkan dengan koma. Contoh: AC, WiFi, TV, Kulkas</p>
                <input type="text" id="facilities-input" placeholder="AC, WiFi, TV..."
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <div id="facilities-container"></div>
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Foto Kamar</label>
                <input type="file" name="images[]" multiple accept="image/*"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
                <p class="text-xs text-gray-400 mt-1">Maksimal 2MB per foto. Foto pertama otomatis jadi foto utama.</p>
            </div>

        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                Simpan Kamar
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