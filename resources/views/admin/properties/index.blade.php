@extends('layouts.admin')

@section('title', 'Manajemen Properti')

@section('content')
<div class="mb-6">
    <h2 class="text-2xl font-bold text-gray-800">Manajemen Properti</h2>
    <p class="text-gray-500 text-sm mt-1">Kelola semua properti terdaftar</p>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama properti..."
           class="flex-1 min-w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
    <select name="status" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <option value="">Semua Status</option>
        <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Active</option>
        <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
        <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactive</option>
    </select>
    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Filter</button>
    <a href="{{ route('admin.properties.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Reset</a>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Owner</th>
                    <th class="px-6 py-3 text-left">Kota</th>
                    <th class="px-6 py-3 text-left">Tipe</th>
                    <th class="px-6 py-3 text-left">Kamar</th>
                    <th class="px-6 py-3 text-left">Status</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($properties as $property)
                @php
                    $statusColors = [
                        'active'   => 'bg-green-100 text-green-700',
                        'inactive' => 'bg-gray-100 text-gray-500',
                        'pending'  => 'bg-yellow-100 text-yellow-700',
                    ];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $property->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $property->owner->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $property->city }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ ucfirst(str_replace('_', ' ', $property->type)) }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $property->rooms_count }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $statusColors[$property->status] ?? '' }}">
                            {{ ucfirst($property->status) }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.properties.show', $property) }}"
                               class="text-xs text-red-600 hover:underline">Detail</a>

                            @if($property->status === 'pending')
                            <form method="POST" action="{{ route('admin.properties.approve', $property) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-green-600 hover:underline">Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.properties.reject', $property) }}">
                                @csrf @method('PATCH')
                                <button type="submit" class="text-xs text-yellow-600 hover:underline">Tolak</button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('admin.properties.destroy', $property) }}"
                                  onsubmit="return confirm('Hapus properti {{ $property->name }}?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-xs text-red-400 hover:text-red-600">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $properties->links() }}
    </div>
</div>
@endsection