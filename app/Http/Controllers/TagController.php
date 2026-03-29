<?php

namespace App\Http\Controllers;

use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TagController extends Controller
{
    public function index()
    {
        $tags = Tag::where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return view('tags.index', compact('tags'));
    }

    public function create()
    {
        return view('tags.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        Tag::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'color' => $validated['color'],
        ]);

        return redirect()->route('tags.index')->with('success', 'Etichetta creata con successo.');
    }

    public function edit(Tag $tag)
    {
        $this->authorizeOwner($tag);

        return view('tags.edit', compact('tag'));
    }

    public function update(Request $request, Tag $tag)
    {
        $this->authorizeOwner($tag);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'required|string|regex:/^#[0-9A-Fa-f]{6}$/',
        ]);

        $tag->update($validated);

        return redirect()->route('tags.index')->with('success', 'Etichetta aggiornata.');
    }

    public function destroy(Tag $tag)
    {
        $this->authorizeOwner($tag);

        $tag->delete();

        return redirect()->route('tags.index')->with('success', 'Etichetta eliminata.');
    }

    public function show(Tag $tag)
    {
        $this->authorizeOwner($tag);

        $items = $tag->items()->with(['collection', 'location'])->orderBy('name')->get();

        return view('tags.show', compact('tag', 'items'));
    }

    private function authorizeOwner(Tag $tag): void
    {
        abort_if($tag->user_id !== Auth::id(), 403);
    }
}
