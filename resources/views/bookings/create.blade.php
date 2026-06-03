@extends('layouts.app')

@section('title', 'Pesan Kamar')

@section('content')
<div class="max-w-4xl mx-auto px-4 py-10">

    <div class="mb-6">
        <a href="{{ route('properties.show', $room->property) }}" class="text-sm text-indigo-600 hover:underline">← Kembali</a>
        <h1 class="text-2xl font-bold text-gray-800 mt-2">Pesan Kamar</h1>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- Form --}}
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-6">
                <form method="POST" action="{{ route('bookings.store') }}">
                    @csrf
                    <input type="hidden" name="room_id" value="{{ $room->id }}">

                    <h2 class="font-semibold text-gray-700 mb-4">Data Tamu</h2>
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                            <input type="text" name="guest_name" value="{{ old('guest_name', auth()->user()->name) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('guest_name') border-red-400 @enderror">
                            @error('guest_name') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Email <span class="text-red-500">*</span></label>
                                <input type="email" name="guest_email" value="{{ old('guest_email', auth()->user()->email) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('guest_email') border-red-400 @enderror">
                                @error('guest_email') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">No. HP <span class="text-red-500">*</span></label>
                                <input type="text" name="guest_phone" value="{{ old('guest_phone', auth()->user()->phone) }}"
                                       class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('guest_phone') border-red-400 @enderror">
                                @error('guest_phone') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                            </div>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Jumlah Tamu <span class="text-red-500">*</span></label>
                            <input type="number" name="guest_count" value="{{ old('guest_count', 1) }}" min="1" max="{{ $room->capacity }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <p class="text-xs text-gray-400 mt-1">Maksimal {{ $room->capacity }} orang</p>
                        </div>
                    </div>

                    <h2 class="font-semibold text-gray-700 mt-6 mb-4">Tanggal Menginap</h2>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-in <span class="text-red-500">*</span></label>
                            <input type="date" name="check_in" value="{{ old('check_in') }}" min="{{ date('Y-m-d') }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('check_in') border-red-400 @enderror">
                            @error('check_in') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-1">Check-out <span class="text-red-500">*</span></label>
                            <input type="date" name="check_out" value="{{ old('check_out') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                                   class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 @error('check_out') border-red-400 @enderror">
                            @error('check_out') <p class="text-red-500 text-xs mt-1">{{ $message }}</p> @enderror
                        </div>
                    </div>

                    <div class="mt-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Permintaan Khusus</label>
                        <textarea name="special_request" rows="3" placeholder="Contoh: kamar lantai atas, extra bed, dll..."
                                  class="w-full border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">{{ old('special_request') }}</textarea>
                    </div>

                    <button type="submit"
                            class="mt-6 w-full bg-indigo-600 text-white py-3 rounded-lg font-medium hover:bg-indigo-700">
                        Buat Booking
                    </button>
                </form>
            </div>
        </div>

        {{-- Ringkasan Kamar --}}
        <div>
            <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-5 sticky top-24">
                <h3 class="font-semibold text-gray-700 mb-4">Ringkasan</h3>
                <div class="space-y-3 text-sm">
                    <div>
                        <p class="text-gray-400">Properti</p>
                        <p class="font-medium text-gray-700">{{ $room->property->name }}</p>
                    </div>
                    <div>
                        <p class="text-gray-400">Kamar</p>
                        <p class="font-medium text-gray-700">{{ $room->name }}</p>
                    </div>
                    @if($room->bed_type)
                    <div>
                        <p class="text-gray-400">Tipe Kasur</p>
                        <p class="font-medium text-gray-700">{{ $room->bed_type }}</p>
                    </div>
                    @endif
                    <div>
                        <p class="text-gray-400">Kapasitas</p>
                        <p class="font-medium text-gray-700">{{ $room->capacity }} orang</p>
                    </div>
                    <div class="pt-3 border-t">
                        <p class="text-gray-400">Harga per Malam</p>
                        <p class="text-xl font-bold text-indigo-600">
                            Rp {{ number_format($room->price_per_night, 0, ',', '.') }}
                        </p>
                    </div>
                    @if($room->facilities->isNotEmpty())
                    <div class="pt-3 border-t">
                        <p class="text-gray-400 mb-2">Fasilitas</p>
                        <div class="flex flex-wrap gap-1">
                            @foreach($room->facilities as $facility)
                                <span class="text-xs bg-gray-100 text-gray-600 px-2 py-1 rounded-full">{{ $facility->name }}</span>
                            @endforeach
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection