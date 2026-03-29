<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <a href="{{ route('collections.index') }}" class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition">
                    <div class="text-indigo-600 text-3xl font-bold mb-1">
                        {{ \App\Models\Collection::where('user_id', auth()->id())->count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Collezioni</div>
                    <div class="text-gray-400 text-sm mt-1">Liste di oggetti</div>
                </a>

                <a href="{{ route('items.index') }}" class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition">
                    <div class="text-emerald-600 text-3xl font-bold mb-1">
                        {{ \App\Models\Item::where('user_id', auth()->id())->count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Oggetti</div>
                    <div class="text-gray-400 text-sm mt-1">In tutte le collezioni</div>
                </a>

                <a href="{{ route('locations.index') }}" class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition">
                    <div class="text-amber-600 text-3xl font-bold mb-1">
                        {{ \App\Models\Location::where('user_id', auth()->id())->count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Luoghi</div>
                    <div class="text-gray-400 text-sm mt-1">Dove sono gli oggetti</div>
                </a>

                <a href="{{ route('tags.index') }}" class="bg-white rounded-lg shadow-sm p-6 border border-gray-200 hover:shadow-md transition">
                    <div class="text-rose-600 text-3xl font-bold mb-1">
                        {{ \App\Models\Tag::where('user_id', auth()->id())->count() }}
                    </div>
                    <div class="text-gray-600 font-medium">Etichette</div>
                    <div class="text-gray-400 text-sm mt-1">Per categorizzare</div>
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-8">
                <h3 class="text-lg font-semibold text-gray-800 mb-4">Ricerca rapida</h3>
                <form action="{{ route('search.index') }}" method="GET">
                    <div class="flex gap-3">
                        <input
                            type="text"
                            name="q"
                            placeholder="Cerca un oggetto per nome, luogo, etichetta..."
                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                        >
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            Cerca
                        </button>
                    </div>
                </form>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Ultime collezioni</h3>
                        <a href="{{ route('collections.create') }}" class="text-sm text-indigo-600 hover:underline">+ Nuova</a>
                    </div>
                    @php $recentCollections = \App\Models\Collection::where('user_id', auth()->id())->withCount('items')->latest()->take(5)->get(); @endphp
                    @forelse ($recentCollections as $col)
                        <a href="{{ route('collections.show', $col) }}" class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600">
                            <span>{{ $col->name }}</span>
                            <span class="text-xs text-gray-400">{{ $col->items_count }} oggetti</span>
                        </a>
                    @empty
                        <p class="text-gray-400 text-sm">Nessuna collezione ancora. <a href="{{ route('collections.create') }}" class="text-indigo-600">Creane una!</a></p>
                    @endforelse
                </div>

                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Ultimi oggetti aggiunti</h3>
                        <a href="{{ route('items.create') }}" class="text-sm text-indigo-600 hover:underline">+ Nuovo</a>
                    </div>
                    @php $recentItems = \App\Models\Item::where('user_id', auth()->id())->with(['collection', 'location'])->latest()->take(5)->get(); @endphp
                    @forelse ($recentItems as $item)
                        <a href="{{ route('items.show', $item) }}" class="flex justify-between items-center py-2 border-b last:border-0 hover:text-indigo-600">
                            <span>{{ $item->name }}</span>
                            <span class="text-xs text-gray-400">{{ $item->location?->name ?? 'Nessun luogo' }}</span>
                        </a>
                    @empty
                        <p class="text-gray-400 text-sm">Nessun oggetto ancora. <a href="{{ route('items.create') }}" class="text-indigo-600">Aggiungine uno!</a></p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
