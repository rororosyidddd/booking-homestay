@extends('layouts.admin')

@section('title', 'Manajemen User')

@section('content')
<div class="flex items-center justify-between mb-6">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">Manajemen User</h2>
        <p class="text-gray-500 text-sm mt-1">Kelola semua user terdaftar</p>
    </div>
</div>

{{-- Filter --}}
<form method="GET" class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 mb-5 flex gap-3 flex-wrap">
    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama / email..."
           class="flex-1 min-w-40 border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
    <select name="role" class="border border-gray-300 rounded-lg px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-red-500">
        <option value="">Semua Role</option>
        <option value="guest" {{ request('role') === 'guest' ? 'selected' : '' }}>Guest</option>
        <option value="owner" {{ request('role') === 'owner' ? 'selected' : '' }}>Owner</option>
        <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
    </select>
    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-lg text-sm hover:bg-red-700">Filter</button>
    <a href="{{ route('admin.users.index') }}" class="bg-gray-100 text-gray-600 px-4 py-2 rounded-lg text-sm hover:bg-gray-200">Reset</a>
</form>

<div class="bg-white rounded-xl shadow-sm border border-gray-100">
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="bg-gray-50 text-gray-500 text-xs uppercase">
                <tr>
                    <th class="px-6 py-3 text-left">Nama</th>
                    <th class="px-6 py-3 text-left">Email</th>
                    <th class="px-6 py-3 text-left">Role</th>
                    <th class="px-6 py-3 text-left">Terdaftar</th>
                    <th class="px-6 py-3 text-left">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-50">
                @foreach($users as $user)
                @php
                    $roleColors = [
                        'admin' => 'bg-red-100 text-red-700',
                        'owner' => 'bg-blue-100 text-blue-700',
                        'guest' => 'bg-gray-100 text-gray-600',
                    ];
                @endphp
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 font-medium text-gray-700">{{ $user->name }}</td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->email }}</td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-xs font-medium {{ $roleColors[$user->role] ?? '' }}">
                            {{ ucfirst($user->role) }}
                        </span>
                    </td>
                    <td class="px-6 py-4 text-gray-500">{{ $user->created_at->format('d M Y') }}</td>
                    <td class="px-6 py-4 flex items-center gap-2">
                        <a href="{{ route('admin.users.show', $user) }}"
                           class="text-xs text-red-600 hover:underline">Detail</a>

                        @if($user->id !== auth()->id())
                        <form method="POST" action="{{ route('admin.users.destroy', $user) }}"
                              onsubmit="return confirm('Hapus user {{ $user->name }}?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-xs text-red-400 hover:text-red-600">Hapus</button>
                        </form>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <div class="px-6 py-4 border-t">
        {{ $users->links() }}
    </div>
</div>
@endsection