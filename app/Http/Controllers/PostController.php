<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class PostController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('auth', except: ['index', 'show']),
        ];
    }

    public function index()
    {
        $posts = Post::with('user')->latest()->paginate(10);
        $lives = \App\Models\Live::where('is_active', true)->with('user')->get();
        return view('posts.index', compact('posts', 'lives'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        Post::create([
            'user_id' => Auth::id(),
            'title'   => $request->title,
            'body'    => $request->body,
            'image'   => $imagePath,
            'is_live' => false,
        ]);

        return redirect()->route('home')->with('success', 'Post publié !');
    }

    public function show(Post $post)
    {
        $post->load('user', 'comments.user', 'comments.replies.user');
        return view('posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        abort_if(Auth::id() !== $post->user_id, 403);
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        abort_if(Auth::id() !== $post->user_id, 403);

        $request->validate([
            'title' => 'required|string|max:255',
            'body'  => 'required|string',
            'image' => 'nullable|image|max:2048',
        ]);

        $imagePath = $post->image;
        if ($request->hasFile('image')) {
            if ($imagePath) Storage::disk('public')->delete($imagePath);
            $imagePath = $request->file('image')->store('posts', 'public');
        }

        $post->update([
            'title' => $request->title,
            'body'  => $request->body,
            'image' => $imagePath,
        ]);

        return redirect()->route('posts.show', $post)->with('success', 'Post modifié !');
    }

    public function destroy(Post $post)
    {
        abort_if(Auth::id() !== $post->user_id, 403);
        if ($post->image) Storage::disk('public')->delete($post->image);
        $post->delete();
        return redirect()->route('home')->with('success', 'Post supprimé !');
    }
}