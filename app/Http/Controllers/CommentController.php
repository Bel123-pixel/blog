<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\User;
use App\Notifications\MentionNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'post_id'   => 'required|exists:posts,id',
            'body'      => 'required|string|max:1000',
            'parent_id' => 'nullable|exists:comments,id',
        ]);

        $comment = Comment::create([
            'post_id'   => $request->post_id,
            'user_id'   => Auth::id(),
            'parent_id' => $request->parent_id,
            'body'      => $request->body,
        ]);

        // Détecter les @mentions
        preg_match_all('/@(\w+)/', $request->body, $matches);
        foreach ($matches[1] as $username) {
            $mentioned = User::where('username', $username)->first();
            if ($mentioned && $mentioned->id !== Auth::id()) {
                $mentioned->notify(new MentionNotification(Auth::user(), $comment));
            }
        }

        return back()->with('success', 'Commentaire ajouté !');
    }

    public function destroy(Comment $comment)
    {
        abort_if(Auth::id() !== $comment->user_id, 403);
        $comment->delete();
        return back()->with('success', 'Commentaire supprimé !');
    }
}