<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Tag;
use App\Models\Collection;
use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SearchController extends Controller
{
    public function index(Request $request)
    {
        $query = $request->input('q', '');
        $tagFilter = $request->input('tag');
        $collectionFilter = $request->input('collection');
        $locationFilter = $request->input('location');

        $items = Item::where('user_id', Auth::id())
            ->with(['collection', 'location', 'tags'])
            ->when($query !== '', fn($q) => $q->search($query))
            ->when($tagFilter, fn($q) => $q->whereHas('tags', fn($q) => $q->where('id', $tagFilter)))
            ->when($collectionFilter, fn($q) => $q->where('collection_id', $collectionFilter))
            ->when($locationFilter, fn($q) => $q->where('location_id', $locationFilter))
            ->orderBy('name')
            ->paginate(20)
            ->withQueryString();

        $tags = Tag::where('user_id', Auth::id())->orderBy('name')->get();
        $collections = Collection::where('user_id', Auth::id())->orderBy('name')->get();
        $locations = Location::where('user_id', Auth::id())->orderBy('name')->get();

        return view('search.index', compact('items', 'tags', 'collections', 'locations', 'query', 'tagFilter', 'collectionFilter', 'locationFilter'));
    }
}
