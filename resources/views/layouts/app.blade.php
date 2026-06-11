<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Kinela Mundial') — {{ config('app.name') }}</title>
    <x-favicons />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="wc-body">
    <nav class="wc-nav">
        <div class="max-w-6xl mx-auto px-4 py-3 flex items-center justify-between">
            <a href="{{ route('ranking') }}" class="wc-nav-brand text-lg sm:text-xl">
                Kinela Mundial 2026
            </a>
            <div class="flex items-center gap-4 text-sm">
                <a href="{{ route('ranking') }}" class="wc-nav-link">Ranking</a>
                @auth
                    <a href="{{ route('admin.dashboard') }}" class="wc-nav-link">Admin</a>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="wc-nav-link hover:text-rose-400">Salir</button>
                    </form>
                @else
                    <a href="{{ route('login') }}" class="wc-btn-gold text-sm !py-1.5 !px-3">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <main class="max-w-7xl mx-auto px-4 py-8">
        @if (session('success'))
            <div class="wc-alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="wc-alert-error">
                <ul class="list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @yield('content')
    </main>
</body>
</html>
