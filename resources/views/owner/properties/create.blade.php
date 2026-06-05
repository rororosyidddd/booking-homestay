@extends('layouts.owner')

@section('title', isset($property) ? 'Edit Properti' : 'Tambah Properti')

@section('content')
<div class="mb-6">
    <a href="{{ route('owner.properties.index') }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
    <h2 class="text-2xl font-bold text-gray-800 mt-2">{{ isset($property) ? 'Edit Properti' : 'Tambah Properti' }}</h2>
</div>

<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ isset($property) ? route('owner.properties.update', $property) : route('owner.properties.store') }}" enctype="multipart/form-data">
        @csrf
        @if(isset($property)) @method('PUT') @endif

        <div class="space-y-5">
            {{-- Nama --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Nama Properti <span class="text-red-500">*</span></label>
                <input type="text" name="name" value="{{ old('name', $property->name ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('name') border-red-400 @enderror">
                @error('name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Tipe --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Tipe <span class="text-red-500">*</span></label>
                <select name="type" class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    @foreach(['hotel', 'villa', 'guest_house', 'kost', 'resort', 'apartment'] as $type)
                        <option value="{{ $type }}" {{ old('type', $property->type ?? '') === $type ? 'selected' : '' }}>
                            {{ ucfirst(str_replace('_', ' ', $type)) }}
                        </option>
                    @endforeach
                </select>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Deskripsi</label>
                <textarea name="description" rows="3"
                          class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('description', $property->description ?? '') }}</textarea>
            </div>

            {{-- Alamat --}}
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1">Alamat <span class="text-red-500">*</span></label>
                <input type="text" name="address" value="{{ old('address', $property->address ?? '') }}"
                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('address') border-red-400 @enderror">
                @error('address') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
            </div>

            {{-- Kota & Provinsi --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Kota <span class="text-red-500">*</span></label>
                    <input type="text" name="city" value="{{ old('city', $property->city ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('city') border-red-400 @enderror">
                    @error('city') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Provinsi <span class="text-red-500">*</span></label>
                    <input type="text" name="province" value="{{ old('province', $property->province ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('province') border-red-400 @enderror">
                    @error('province') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                </div>
            </div>

            {{-- Telepon & Email --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">No. Telepon</label>
                    <input type="text" name="phone" value="{{ old('phone', $property->phone ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
                    <input type="email" name="email" value="{{ old('email', $property->email ?? '') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>

            {{-- Jam Check-in & Check-out --}}
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Check-in <span class="text-red-500">*</span></label>
                    <input type="time" name="check_in_time" value="{{ old('check_in_time', $property->check_in_time ?? '14:00') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jam Check-out <span class="text-red-500">*</span></label>
                    <input type="time" name="check_out_time" value="{{ old('check_out_time', $property->check_out_time ?? '12:00') }}"
                           class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>
            </div>
        </div>

        {{-- Cover Image --}}
        <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Foto Cover</label>
        @if(isset($property) && $property->cover_image)
            <img src="{{ Storage::url($property->cover_image) }}" alt="Cover"
                class="w-full h-40 object-cover rounded-lg mb-2">
        @endif
        <input type="file" name="cover_image" accept="image/*"
            class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm">
        <p class="text-xs text-gray-400 mt-1">Maksimal 2MB. Format: JPG, PNG.</p>
        </div>

        <div class="mt-6 flex gap-3">
            <button type="submit" class="bg-indigo-600 text-white px-6 py-2 rounded-lg text-sm font-medium hover:bg-indigo-700">
                {{ isset($property) ? 'Update Properti' : 'Simpan Properti' }}
            </button>
            <a href="{{ route('owner.properties.index') }}" class="bg-gray-100 text-gray-600 px-6 py-2 rounded-lg text-sm font-medium hover:bg-gray-200">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
