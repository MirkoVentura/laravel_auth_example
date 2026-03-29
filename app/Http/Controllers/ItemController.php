<?php

namespace App\Http\Controllers;

use App\Models\Collection;
use App\Models\CollectionAttribute;
use App\Models\Item;
use App\Models\ItemAttribute;
use App\Models\Location;
use App\Models\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ItemController extends Controller
{
    public function index()
    {
        $items = Item::where('user_id', Auth::id())
            ->with(['collection.attributes', 'location', 'tags', 'itemAttributes.definition'])
            ->orderBy('name')
            ->paginate(20);

        return view('items.index', compact('items'));
    }

    public function create(Request $request)
    {
        $collections = Collection::where('user_id', Auth::id())->with('attributes')->orderBy('name')->get();
        $locations   = Location::where('user_id', Auth::id())->orderBy('name')->get();
        $tags        = Tag::where('user_id', Auth::id())->orderBy('name')->get();
        $selectedCollection = $request->query('collection_id');

        return view('items.create', compact('collections', 'locations', 'tags', 'selectedCollection'));
    }

    public function store(Request $request)
    {
        $collection = Collection::where('id', $request->collection_id)
            ->where('user_id', Auth::id())
            ->with('attributes')
            ->firstOrFail();

        $rules = [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'collection_id' => 'required|exists:collections,id',
            'location_id'   => 'nullable|exists:locations,id',
            'quantity'      => 'required|integer|min:1',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
        ];

        foreach ($collection->attributes as $attr) {
            $rules["attr_{$attr->key}"] = $attr->required ? 'required' : 'nullable';
            if ($attr->type === 'number') {
                $rules["attr_{$attr->key}"] .= '|numeric';
            } elseif ($attr->type === 'date') {
                $rules["attr_{$attr->key}"] .= '|date';
            } elseif ($attr->type === 'url') {
                $rules["attr_{$attr->key}"] .= '|url';
            }
        }

        $validated = $request->validate($rules);

        $item = Item::create([
            'user_id'       => Auth::id(),
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'collection_id' => $validated['collection_id'],
            'location_id'   => $validated['location_id'] ?? null,
            'quantity'      => $validated['quantity'],
        ]);

        if (!empty($validated['tags'])) {
            $item->tags()->sync($validated['tags']);
        }

        $this->saveItemAttributes($item, $collection, $request);

        return redirect()->route('items.show', $item)->with('success', 'Oggetto aggiunto con successo.');
    }

    public function show(Item $item)
    {
        $this->authorizeOwner($item);

        $item->load(['collection.attributes', 'location', 'tags', 'itemAttributes.definition']);

        return view('items.show', compact('item'));
    }

    public function edit(Item $item)
    {
        $this->authorizeOwner($item);

        $collections = Collection::where('user_id', Auth::id())->with('attributes')->orderBy('name')->get();
        $locations   = Location::where('user_id', Auth::id())->orderBy('name')->get();
        $tags        = Tag::where('user_id', Auth::id())->orderBy('name')->get();

        $item->load(['collection.attributes', 'itemAttributes.definition']);

        return view('items.edit', compact('item', 'collections', 'locations', 'tags'));
    }

    public function update(Request $request, Item $item)
    {
        $this->authorizeOwner($item);

        $collection = Collection::where('id', $request->collection_id)
            ->where('user_id', Auth::id())
            ->with('attributes')
            ->firstOrFail();

        $rules = [
            'name'          => 'required|string|max:255',
            'description'   => 'nullable|string',
            'collection_id' => 'required|exists:collections,id',
            'location_id'   => 'nullable|exists:locations,id',
            'quantity'      => 'required|integer|min:1',
            'tags'          => 'nullable|array',
            'tags.*'        => 'exists:tags,id',
        ];

        foreach ($collection->attributes as $attr) {
            $rules["attr_{$attr->key}"] = $attr->required ? 'required' : 'nullable';
            if ($attr->type === 'number') {
                $rules["attr_{$attr->key}"] .= '|numeric';
            } elseif ($attr->type === 'date') {
                $rules["attr_{$attr->key}"] .= '|date';
            } elseif ($attr->type === 'url') {
                $rules["attr_{$attr->key}"] .= '|url';
            }
        }

        $validated = $request->validate($rules);

        $item->update([
            'name'          => $validated['name'],
            'description'   => $validated['description'] ?? null,
            'collection_id' => $validated['collection_id'],
            'location_id'   => $validated['location_id'] ?? null,
            'quantity'      => $validated['quantity'],
        ]);

        $item->tags()->sync($validated['tags'] ?? []);

        $this->saveItemAttributes($item, $collection, $request);

        return redirect()->route('items.show', $item)->with('success', 'Oggetto aggiornato.');
    }

    public function destroy(Item $item)
    {
        $this->authorizeOwner($item);

        $item->delete();

        return redirect()->route('items.index')->with('success', 'Oggetto eliminato.');
    }

    private function saveItemAttributes(Item $item, Collection $collection, Request $request): void
    {
        foreach ($collection->attributes as $attr) {
            $raw = $request->input("attr_{$attr->key}");

            // boolean: checkbox non inviato = false
            if ($attr->type === 'boolean') {
                $raw = $request->boolean("attr_{$attr->key}") ? '1' : '0';
            }

            ItemAttribute::updateOrCreate(
                ['item_id' => $item->id, 'collection_attribute_id' => $attr->id],
                ['value' => $raw]
            );
        }
    }

    private function authorizeOwner(Item $item): void
    {
        abort_if($item->user_id !== Auth::id(), 403);
    }
}
