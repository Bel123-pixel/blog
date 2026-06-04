<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-white shadow mb-6">
    <div class="max-w-4xl mx-auto px-4 py-3 flex items-center justify-between">
        <a href="{{ route('home') }}" class="text-xl font-bold text-indigo-600">Blog ÉPAC</a>
        <div class="flex items-center gap-4">
            @auth
                <a href="{{ route('posts.create') }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm">+ Post</a>
                <a href="{{ route('lives.index') }}" class="text-gray-600 text-sm hover:text-indigo-600">Lives</a>
                <!-- Notifications -->
                <div class="relative">
                    <a href="{{ route('dashboard') }}" class="text-gray-600 text-sm hover:text-indigo-600 relative">
                        Notifs
                       @if(auth()->user()->unreadNotifications()->count() > 0)
                            <span class="absolute -top-2 -right-3 bg-red-500 text-white text-xs rounded-full w-4 h-4 flex items-center justify-center">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </a>
                </div>
                <span class="text-gray-500 text-sm">{{ auth()->user()->name }}</span>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button class="text-sm text-gray-500 hover:text-red-500">Déconnexion</button>
                </form>
            @else
                <a href="{{ route('login') }}" class="text-sm text-gray-600 hover:text-indigo-600">Connexion</a>
                <a href="{{ route('register') }}" class="bg-indigo-600 text-white px-3 py-1 rounded text-sm">Inscription</a>
            @endauth
        </div>
    </div>
</nav>

<main class="max-w-4xl mx-auto px-4">
    @if(session('success'))
        <div class="bg-green-100 text-green-800 px-4 py-2 rounded mb-4">{{ session('success') }}</div>
    @endif
    @yield('content')
</main>

</body>
</html>