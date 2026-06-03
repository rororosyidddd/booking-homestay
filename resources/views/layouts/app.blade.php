<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Beranda') — {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-50 font-sans">

    {{-- Navbar --}}
    <nav class="bg-white shadow-sm sticky top-0 z-50">
        <div class="max-w-6xl mx-auto px-4 py-4 flex items-center justify-between">
            <a href="{{ url('/') }}" class="text-xl font-bold text-indigo-600">
                {{ config('app.name') }}
            </a>

            <div class="flex items-center gap-4">
                @auth
                    <a href="{{ route('properties.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Cari Penginapan</a>
                    <a href="{{ route('bookings.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Booking Saya</a>
                @if(auth()->user()->isOwner() || auth()->user()->isAdmin())
                    <a href="{{ route('owner.dashboard') }}" class="text-sm text-gray-600 hover:text-indigo-600">Dashboard</a>
                @endif
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                @csrf
                    <button type="submit" class="text-sm text-gray-600 hover:text-indigo-600">Logout</button>
                    </form>
                @else
                    <a href="{{ route('properties.index') }}" class="text-sm text-gray-600 hover:text-indigo-600">Cari Penginapan</a>
                    <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">Login</a>
                    <a href="{{ route('register') }}" class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Daftar</a>
                @endauth
            </div>
        </div>
    </nav>

    {{-- Content --}}
    <main>
        @yield('content')
    </main>

    {{-- Footer --}}
    <footer class="bg-white border-t mt-16">
        <div class="max-w-6xl mx-auto px-4 py-8 text-center text-sm text-gray-400">
            © {{ date('Y') }} {{ config('app.name') }}. All rights reserved.
        </div>
    </footer>

</body>
</html>