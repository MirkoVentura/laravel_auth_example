<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionAttribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CollectionAttributeController extends Controller
{
    public function store(Request $request, Collection $collection)
    {
        abort_if($collection->user_id !== Auth::id(), 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'type'     => 'required|in:text,number,date,boolean,url',
            'required' => 'boolean',
        ]);

        $collection->attributes()->create([
            'name'       => $validated['name'],
            'key'        => CollectionAttribute::makeKey($validated['name']),
            'type'       => $validated['type'],
            'required'   => $request->boolean('required'),
            'sort_order' => $collection->attributes()->count(),
        ]);

        return back()->with('success', 'Caratteristica aggiunta.');
    }

    public function update(Request $request, Collection $collection, CollectionAttribute $attribute)
    {
        abort_if($collection->user_id !== Auth::id(), 403);
        abort_if($attribute->collection_id !== $collection->id, 403);

        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'type'     => 'required|in:text,number,date,boolean,url',
            'required' => 'boolean',
        ]);

        $attribute->update([
            'name'     => $validated['name'],
            'type'     => $validated['type'],
            'required' => $request->boolean('required'),
        ]);

        return back()->with('success', 'Caratteristica aggiornata.');
    }

    public function destroy(Collection $collection, CollectionAttribute $attribute)
    {
        abort_if($collection->user_id !== Auth::id(), 403);
        abort_if($attribute->collection_id !== $collection->id, 403);

        $attribute->delete();

        return back()->with('success', 'Caratteristica eliminata.');
    }
}
