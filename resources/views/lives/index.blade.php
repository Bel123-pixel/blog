@extends('layouts.app')

@section('content')

@if($activeLives->count())
<div class="mb-6">
    <h2 class="text-lg font-bold text-red-600 mb-3">🔴 Lives en cours</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($activeLives as $live)
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <div class="flex items-center gap-2 mb-1">
                <span class="inline-block w-2 h-2 rounded-full bg-red-500 animate-pulse"></span>
                <p class="font-semibold">{{ $live->title }}</p>
            </div>
            <p class="text-sm text-gray-500">Streamer : {{ $live->user->name }}</p>
        </div>
        @endforeach
    </div>
</div>
@else
    <p class="text-gray-400 text-sm mb-6">Aucun live en cours.</p>
@endif

<div class="bg-white rounded-lg shadow p-6">
    <div class="flex justify-between items-center mb-4">
        <h2 class="text-xl font-bold">Mes lives</h2>
        <a href="{{ route('lives.create') }}"
           class="bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
            + Nouveau live
        </a>
    </div>

    @forelse($myLives as $live)
    <div class="border rounded p-4 mb-3 flex items-center justify-between">
        <div>
            <p class="font-semibold">{{ $live->title }}</p>
            <span class="text-xs px-2 py-0.5 rounded {{ $live->is_active ? 'bg-red-100 text-red-600' : 'bg-gray-100 text-gray-500' }}">
                {{ $live->is_active ? '🔴 En direct' : '⚫ Hors ligne' }}
            </span>
        </div>
        <div class="flex gap-2">
            <form method="POST" action="{{ route('lives.toggle', $live) }}">
                @csrf @method('PATCH')
                <button class="text-sm px-3 py-1 rounded border {{ $live->is_active ? 'border-red-300 text-red-600 hover:bg-red-50' : 'border-green-300 text-green-600 hover:bg-green-50' }}">
                    {{ $live->is_active ? 'Arrêter' : 'Démarrer' }}
                </button>
            </form>
            <form method="POST" action="{{ route('lives.destroy', $live) }}">
                @csrf @method('DELETE')
                <button onclick="return confirm('Supprimer ?')"
                    class="text-sm px-3 py-1 rounded border border-red-200 text-red-500 hover:bg-red-50">
                    Supprimer
                </button>
            </form>
        </div>
    </div>
    @empty
        <p class="text-gray-400 text-sm">Vous n'avez pas encore de live.</p>
    @endforelse
</div>

@endsection