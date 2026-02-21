<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionController extends Controller
{
    public function index()
    {
        $collections = Collection::where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return view('collections.index', compact('collections'));
    }

    public function create()
    {
        return view('collections.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Collection::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('collections.index')->with('success', 'Collezione creata con successo.');
    }

    public function show(Collection $collection)
    {
        $this->authorizeOwner($collection);

        $items = $collection->items()
            ->with(['location', 'tags'])
            ->orderBy('name')
            ->get();

        return view('collections.show', compact('collection', 'items'));
    }

    public function edit(Collection $collection)
    {
        $this->authorizeOwner($collection);

        return view('collections.edit', compact('collection'));
    }

    public function update(Request $request, Collection $collection)
    {
        $this->authorizeOwner($collection);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $collection->update($validated);

        return redirect()->route('collections.show', $collection)->with('success', 'Collezione aggiornata.');
    }

    public function destroy(Collection $collection)
    {
        $this->authorizeOwner($collection);

        $collection->delete();

        return redirect()->route('collections.index')->with('success', 'Collezione eliminata.');
    }

    private function authorizeOwner(Collection $collection): void
    {
        abort_if($collection->user_id !== Auth::id(), 403);
    }
}
