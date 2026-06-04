@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6 max-w-lg mx-auto">
    <h1 class="text-2xl font-bold mb-6">Créer un live</h1>

    <form method="POST" action="{{ route('lives.store') }}">
        @csrf
        <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Titre du live</label>
            <input type="text" name="title" value="{{ old('title') }}"
                class="w-full border rounded px-3 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-400"
                placeholder="Ex: Cours de réseaux en direct" required>
            @error('title')<p class="text-red-500 text-sm mt-1">{{ $message }}</p>@enderror
        </div>

        <div class="flex gap-3">
            <button type="submit"
                class="bg-indigo-600 text-white px-6 py-2 rounded hover:bg-indigo-700">
                Créer
            </button>
            <a href="{{ route('lives.index') }}"
               class="px-6 py-2 rounded border text-gray-600 hover:bg-gray-50">
                Annuler
            </a>
        </div>
    </form>
</div>
@endsection