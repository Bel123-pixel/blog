
@extends('layouts.app')

@section('content')

{{-- Lives en cours --}}
@if($lives->count())
<div class="mb-6">
    <h2 class="text-lg font-semibold text-red-600 mb-2">🔴 En direct</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        @foreach($lives as $live)
        <div class="bg-white rounded-lg shadow p-4 border-l-4 border-red-500">
            <p class="font-semibold">{{ $live->title }}</p>
            <p class="text-sm text-gray-500">par {{ $live->user->name }}</p>
        </div>
        @endforeach
    </div>
</div>
@endif

{{-- Posts --}}
<h2 class="text-lg font-semibold text-gray-700 mb-4">Derniers articles</h2>
<div class="space-y-4">
    @forelse($posts as $post)
    <div class="bg-white rounded-lg shadow p-5">
        @if($post->image)
            <img src="{{ Storage::url($post->image) }}" class="w-full h-48 object-cover rounded mb-3">
        @endif
        <h3 class="text-xl font-bold text-gray-800">
            <a href="{{ route('posts.show', $post) }}" class="hover:text-indigo-600">{{ $post->title }}</a>
        </h3>
        <p class="text-gray-500 text-sm mt-1">par {{ $post->user->name }} · {{ $post->created_at->diffForHumans() }}</p>
        <p class="text-gray-600 mt-2">{{ Str::limit($post->body, 150) }}</p>
        <a href="{{ route('posts.show', $post) }}" class="text-indigo-600 text-sm mt-2 inline-block">Lire la suite →</a>
    </div>
    @empty
        <p class="text-gray-500">Aucun article pour l'instant.</p>
    @endforelse
</div>

<div class="mt-6">{{ $posts->links() }}</div>

@endsection