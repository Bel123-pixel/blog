<?php

namespace App\Http\Controllers;

use App\Models\Live;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LiveController extends Controller
{
    public function index()
    {
        $myLives = Live::where('user_id', Auth::id())->latest()->get();
        $activeLives = Live::where('is_active', true)->with('user')->get();
        return view('lives.index', compact('myLives', 'activeLives'));
    }

    public function create()
    {
        return view('lives.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
        ]);

        Live::create([
            'user_id'   => Auth::id(),
            'title'     => $request->title,
            'is_active' => false,
        ]);

        return redirect()->route('lives.index')->with('success', 'Live créé !');
    }

    public function toggle(Live $live)
    {
        abort_if(Auth::id() !== $live->user_id, 403);
        $live->update(['is_active' => !$live->is_active]);
        $status = $live->is_active ? 'démarré' : 'arrêté';
        return back()->with('success', "Live $status !");
    }

    public function destroy(Live $live)
    {
        abort_if(Auth::id() !== $live->user_id, 403);
        $live->delete();
        return back()->with('success', 'Live supprimé !');
    }
}