<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Cerca oggetti</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            {{-- Search form --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
                <form method="GET" action="{{ route('search.index') }}">
                    <div class="flex gap-3 mb-4">
                        <input
                            type="text"
                            name="q"
                            value="{{ $query }}"
                            placeholder="Cerca per nome, descrizione, luogo, etichetta..."
                            class="flex-1 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            autofocus
                        >
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            Cerca
                        </button>
                        @if ($query || $tagFilter || $collectionFilter || $locationFilter)
                            <a href="{{ route('search.index') }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50">
                                Azzera
                            </a>
                        @endif
                    </div>

                    {{-- Filters --}}
                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3">
                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Filtro Collezione</label>
                            <select name="collection" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tutte le collezioni</option>
                                @foreach ($collections as $collection)
                                    <option value="{{ $collection->id }}" {{ $collectionFilter == $collection->id ? 'selected' : '' }}>
                                        {{ $collection->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Filtro Luogo</label>
                            <select name="location" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tutti i luoghi</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" {{ $locationFilter == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-500 mb-1">Filtro Etichetta</label>
                            <select name="tag" class="w-full rounded-lg border-gray-300 shadow-sm text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Tutte le etichette</option>
                                @foreach ($tags as $tag)
                                    <option value="{{ $tag->id }}" {{ $tagFilter == $tag->id ? 'selected' : '' }}>
                                        {{ $tag->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>
            </div>

            {{-- Results --}}
            @if ($query || $tagFilter || $collectionFilter || $locationFilter)
                <p class="text-sm text-gray-500 mb-4">
                    {{ $items->total() }} risultati trovati
                    @if ($query) per "<strong>{{ $query }}</strong>" @endif
                </p>

                @forelse ($items as $item)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3 flex justify-between items-start">
                        <div>
                            <a href="{{ route('items.show', $item) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                                {{ $item->name }}
                            </a>
                            @if ($item->description)
                                <p class="text-gray-500 text-sm mt-1">{{ Str::limit($item->description, 100) }}</p>
                            @endif
                            <div class="flex gap-4 mt-2 text-sm text-gray-500">
                                <a href="{{ route('collections.show', $item->collection) }}" class="hover:text-indigo-600">
                                    {{ $item->collection->name }}
                                </a>
                                @if ($item->location)
                                    <a href="{{ route('locations.show', $item->location) }}" class="hover:text-amber-600">
                                        📍 {{ $item->location->name }}
                                    </a>
                                @else
                                    <span class="text-gray-300">Nessun luogo</span>
                                @endif
                                @if ($item->quantity > 1)
                                    <span>x{{ $item->quantity }}</span>
                                @endif
                            </div>
                            @if ($item->tags->isNotEmpty())
                                <div class="flex gap-1 mt-2 flex-wrap">
                                    @foreach ($item->tags as $tag)
                                        <span class="px-2 py-0.5 rounded-full text-xs text-white font-medium" style="background-color: {{ $tag->color }}">
                                            {{ $tag->name }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <a href="{{ route('items.edit', $item) }}" class="text-gray-500 hover:underline text-sm ml-4 flex-shrink-0">Modifica</a>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                        <p class="text-gray-500">Nessun oggetto trovato.</p>
                    </div>
                @endforelse

                <div class="mt-4">{{ $items->links() }}</div>
            @else
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center text-gray-400">
                    Inserisci un termine di ricerca o seleziona un filtro per trovare oggetti.
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
