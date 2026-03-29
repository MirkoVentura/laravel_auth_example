<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LocationController extends Controller
{
    public function index()
    {
        $locations = Location::where('user_id', Auth::id())
            ->withCount('items')
            ->orderBy('name')
            ->get();

        return view('locations.index', compact('locations'));
    }

    public function create()
    {
        return view('locations.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        Location::create([
            'user_id' => Auth::id(),
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
        ]);

        return redirect()->route('locations.index')->with('success', 'Luogo creato con successo.');
    }

    public function show(Location $location)
    {
        $this->authorizeOwner($location);

        $items = $location->items()
            ->with(['collection', 'tags'])
            ->orderBy('name')
            ->get();

        return view('locations.show', compact('location', 'items'));
    }

    public function edit(Location $location)
    {
        $this->authorizeOwner($location);

        return view('locations.edit', compact('location'));
    }

    public function update(Request $request, Location $location)
    {
        $this->authorizeOwner($location);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        $location->update($validated);

        return redirect()->route('locations.show', $location)->with('success', 'Luogo aggiornato.');
    }

    public function destroy(Location $location)
    {
        $this->authorizeOwner($location);

        $location->delete();

        return redirect()->route('locations.index')->with('success', 'Luogo eliminato.');
    }

    private function authorizeOwner(Location $location): void
    {
        abort_if($location->user_id !== Auth::id(), 403);
    }
}
