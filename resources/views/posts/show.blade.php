@extends('layouts.app')

@section('content')
<div class="bg-white rounded-lg shadow p-6 mb-6">

    {{-- Image --}}
    @if($post->image)
        <img src="{{ Storage::url($post->image) }}" class="w-full h-64 object-cover rounded mb-4">
    @endif

    {{-- Header post --}}
    <h1 class="text-3xl font-bold text-gray-800 mb-2">{{ $post->title }}</h1>
    <p class="text-sm text-gray-500 mb-4">
        par <span class="font-medium">{{ $post->user->name }}</span>
        · {{ $post->created_at->diffForHumans() }}
    </p>

    {{-- Actions auteur --}}
    @auth
        @if(auth()->id() === $post->user_id)
        <div class="flex gap-3 mb-4">
            <a href="{{ route('posts.edit', $post) }}"
               class="text-sm bg-yellow-100 text-yellow-700 px-3 py-1 rounded hover:bg-yellow-200">
                Modifier
            </a>
            <form method="POST" action="{{ route('posts.destroy', $post) }}">
                @csrf @method('DELETE')
                <button onclick="return confirm('Supprimer ce post ?')"
                    class="text-sm bg-red-100 text-red-600 px-3 py-1 rounded hover:bg-red-200">
                    Supprimer
                </button>
            </form>
        </div>
        @endif
    @endauth

    {{-- Corps du post --}}
    <div class="prose max-w-none text-gray-700 leading-relaxed">
        {!! nl2br(e($post->body)) !!}
    </div>
</div>

{{-- Section commentaires --}}
<div class="bg-white rounded-lg shadow p-6">
    <h2 class="text-xl font-bold mb-4">
        Commentaires ({{ $post->comments->count() }})
    </h2>

    {{-- Formulaire nouveau commentaire --}}
    @auth
    <form method="POST" action="{{ route('comments.store') }}" class="mb-6">
        @csrf
        <input type="hidden" name="post_id" value="{{ $post->id }}">
        <textarea name="body" rows="3" placeholder="Votre commentaire... (utilisez @username pour mentionner)"
            class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
            required>{{ old('body') }}</textarea>
        @error('body')<p class="text-red-500 text-sm">{{ $message }}</p>@enderror
        <button type="submit"
            class="mt-2 bg-indigo-600 text-white px-4 py-2 rounded text-sm hover:bg-indigo-700">
            Commenter
        </button>
    </form>
    @else
        <p class="text-sm text-gray-500 mb-4">
            <a href="{{ route('login') }}" class="text-indigo-600 hover:underline">Connectez-vous</a> pour commenter.
        </p>
    @endauth

    {{-- Liste des commentaires --}}
    <div class="space-y-4" id="comments">
        @forelse($post->comments as $comment)
        <div class="border rounded p-4" id="comment-{{ $comment->id }}">
            <div class="flex justify-between items-start">
                <div>
                    <span class="font-semibold text-sm">{{ $comment->user->name }}</span>
                    <span class="text-gray-400 text-xs ml-2">@{{ $comment->user->username }}</span>
                    <span class="text-gray-400 text-xs ml-2">· {{ $comment->created_at->diffForHumans() }}</span>
                </div>
                @auth
                    @if(auth()->id() === $comment->user_id)
                    <form method="POST" action="{{ route('comments.destroy', $comment) }}">
                        @csrf @method('DELETE')
                        <button class="text-xs text-red-400 hover:text-red-600">Supprimer</button>
                    </form>
                    @endif
                @endauth
            </div>
            <p class="text-gray-700 text-sm mt-2">{{ $comment->body }}</p>

            {{-- Bouton répondre --}}
            @auth
            <button onclick="toggleReply({{ $comment->id }})"
                class="text-xs text-indigo-500 mt-2 hover:underline">
                Répondre
            </button>

            {{-- Formulaire réponse (caché) --}}
            <div id="reply-form-{{ $comment->id }}" class="hidden mt-3">
                <form method="POST" action="{{ route('comments.store') }}">
                    @csrf
                    <input type="hidden" name="post_id" value="{{ $post->id }}">
                    <input type="hidden" name="parent_id" value="{{ $comment->id }}">
                    <textarea name="body" rows="2"
                        placeholder="Répondre à {{ $comment->user->name }}..."
                        class="w-full border rounded px-3 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-400"
                        required></textarea>
                    <div class="flex gap-2 mt-1">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-3 py-1 rounded text-xs hover:bg-indigo-700">
                            Répondre
                        </button>
                        <button type="button" onclick="toggleReply({{ $comment->id }})"
                            class="text-xs text-gray-500 hover:text-gray-700">
                            Annuler
                        </button>
                    </div>
                </form>
            </div>
            @endauth

            {{-- Réponses imbriquées --}}
            @if($comment->replies->count())
            <div class="ml-6 mt-3 space-y-3 border-l-2 border-indigo-100 pl-4">
                @foreach($comment->replies as $reply)
                <div class="bg-gray-50 rounded p-3">
                    <div class="flex justify-between items-start">
                        <div>
                            <span class="font-semibold text-sm">{{ $reply->user->name }}</span>
                            <span class="text-gray-400 text-xs ml-2">@{{ $reply->user->username }}</span>
                            <span class="text-gray-400 text-xs ml-2">· {{ $reply->created_at->diffForHumans() }}</span>
                        </div>
                        @auth
                            @if(auth()->id() === $reply->user_id)
                            <form method="POST" action="{{ route('comments.destroy', $reply) }}">
                                @csrf @method('DELETE')
                                <button class="text-xs text-red-400 hover:text-red-600">Supprimer</button>
                            </form>
                            @endif
                        @endauth
                    </div>
                    <p class="text-gray-700 text-sm mt-1">{{ $reply->body }}</p>
                </div>
                @endforeach
            </div>
            @endif
        </div>
        @empty
            <p class="text-gray-400 text-sm">Aucun commentaire pour l'instant.</p>
        @endforelse
    </div>
</div>

<script>
function toggleReply(id) {
    const form = document.getElementById('reply-form-' + id);
    form.classList.toggle('hidden');
}
</script>
@endsection