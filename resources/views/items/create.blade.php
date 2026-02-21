<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuovo oggetto</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @if ($collections->isEmpty())
                    <div class="bg-amber-50 border border-amber-200 text-amber-800 rounded-lg p-4 mb-4">
                        Devi prima <a href="{{ route('collections.create') }}" class="underline font-medium">creare una collezione</a> prima di aggiungere oggetti.
                    </div>
                @endif

                <form method="POST" action="{{ route('items.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Nome dell'oggetto">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Descrizione opzionale...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Collezione *</label>
                        <select name="collection_id" required class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Seleziona una collezione...</option>
                            @foreach ($collections as $collection)
                                <option value="{{ $collection->id }}" {{ (old('collection_id', $selectedCollection) == $collection->id) ? 'selected' : '' }}>
                                    {{ $collection->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('collection_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Luogo</label>
                        <select name="location_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Nessun luogo specificato</option>
                            @foreach ($locations as $location)
                                <option value="{{ $location->id }}" {{ old('location_id') == $location->id ? 'selected' : '' }}>
                                    {{ $location->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('location_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Quantità</label>
                        <input type="number" name="quantity" value="{{ old('quantity', 1) }}" min="1"
                            class="w-32 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                        @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    @if ($tags->isNotEmpty())
                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Etichette</label>
                            <div class="flex flex-wrap gap-2">
                                @foreach ($tags as $tag)
                                    <label class="flex items-center gap-2 cursor-pointer">
                                        <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                            {{ in_array($tag->id, old('tags', [])) ? 'checked' : '' }}
                                            class="rounded border-gray-300">
                                        <span class="px-2 py-0.5 rounded-full text-xs text-white font-medium" style="background-color: {{ $tag->color }}">
                                            {{ $tag->name }}
                                        </span>
                                    </label>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="mb-6">
                            <p class="text-sm text-gray-400">
                                Nessuna etichetta disponibile.
                                <a href="{{ route('tags.create') }}" class="text-indigo-600 hover:underline">Crea un'etichetta</a>
                            </p>
                        </div>
                    @endif

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium" {{ $collections->isEmpty() ? 'disabled' : '' }}>
                            Aggiungi oggetto
                        </button>
                        <a href="{{ route('items.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
