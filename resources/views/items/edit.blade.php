<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifica oggetto</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">

                @php
                    $collectionsData = $collections->map(fn($c) => [
                        'id' => $c->id,
                        'attributes' => $c->attributes->map(fn($a) => [
                            'id' => $a->id, 'key' => $a->key, 'name' => $a->name,
                            'type' => $a->type, 'required' => $a->required,
                        ])->values(),
                    ])->keyBy('id');

                    // Valori attuali degli attributi dell'oggetto
                    $currentAttrValues = $item->itemAttributes->mapWithKeys(
                        fn($ia) => ["attr_{$ia->definition?->key}" => $ia->value]
                    );
                @endphp

                <div x-data="itemForm({{ json_encode($collectionsData) }}, '{{ old('collection_id', $item->collection_id) }}')">
                    <form method="POST" action="{{ route('items.update', $item) }}">
                        @csrf @method('PATCH')

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                            <input type="text" name="name" value="{{ old('name', $item->name) }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $item->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Collezione *</label>
                            <select name="collection_id" required x-model="selectedCollectionId"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                @foreach ($collections as $collection)
                                    <option value="{{ $collection->id }}">{{ $collection->name }}</option>
                                @endforeach
                            </select>
                            @error('collection_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Luogo</label>
                            <select name="location_id" class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                <option value="">Nessun luogo specificato</option>
                                @foreach ($locations as $location)
                                    <option value="{{ $location->id }}" {{ old('location_id', $item->location_id) == $location->id ? 'selected' : '' }}>
                                        {{ $location->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('location_id') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Quantità</label>
                            <input type="number" name="quantity" value="{{ old('quantity', $item->quantity) }}" min="1"
                                class="w-32 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('quantity') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        {{-- CAMPI CARATTERISTICHE DINAMICI --}}
                        <template x-if="currentAttributes.length > 0">
                            <div class="border-t border-gray-100 mt-4 pt-4">
                                <h4 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Caratteristiche</h4>
                                <div class="space-y-4">
                                    <template x-for="attr in currentAttributes" :key="attr.id">
                                        <div>
                                            <label class="block text-sm font-medium text-gray-700 mb-1">
                                                <span x-text="attr.name"></span>
                                                <span x-show="attr.required" class="text-red-500"> *</span>
                                                <span class="text-xs text-gray-400 font-normal ml-1" x-text="'(' + typeLabel(attr.type) + ')'"></span>
                                            </label>
                                            <template x-if="attr.type === 'text'">
                                                <input type="text" :name="'attr_' + attr.key"
                                                    :required="attr.required"
                                                    :value="savedValues['attr_' + attr.key] || ''"
                                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </template>
                                            <template x-if="attr.type === 'number'">
                                                <input type="number" step="any" :name="'attr_' + attr.key"
                                                    :required="attr.required"
                                                    :value="savedValues['attr_' + attr.key] || ''"
                                                    class="w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </template>
                                            <template x-if="attr.type === 'date'">
                                                <input type="date" :name="'attr_' + attr.key"
                                                    :required="attr.required"
                                                    :value="savedValues['attr_' + attr.key] || ''"
                                                    class="w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </template>
                                            <template x-if="attr.type === 'url'">
                                                <input type="url" :name="'attr_' + attr.key"
                                                    :required="attr.required"
                                                    :value="savedValues['attr_' + attr.key] || ''"
                                                    placeholder="https://..."
                                                    class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                                            </template>
                                            <template x-if="attr.type === 'boolean'">
                                                <label class="flex items-center gap-2 cursor-pointer">
                                                    <input type="checkbox" :name="'attr_' + attr.key" value="1"
                                                        :checked="savedValues['attr_' + attr.key] == '1'"
                                                        class="rounded border-gray-300 text-indigo-600">
                                                    <span class="text-sm text-gray-600">Sì</span>
                                                </label>
                                            </template>
                                        </div>
                                    </template>
                                </div>
                            </div>
                        </template>

                        @if ($tags->isNotEmpty())
                            <div class="mt-4 mb-6">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Etichette</label>
                                <div class="flex flex-wrap gap-2">
                                    @foreach ($tags as $tag)
                                        <label class="flex items-center gap-2 cursor-pointer">
                                            <input type="checkbox" name="tags[]" value="{{ $tag->id }}"
                                                {{ $item->tags->contains($tag->id) ? 'checked' : '' }}
                                                class="rounded border-gray-300">
                                            <span class="px-2 py-0.5 rounded-full text-xs text-white font-medium" style="background-color: {{ $tag->color }}">
                                                {{ $tag->name }}
                                            </span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <div class="flex gap-3 mt-6">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                                Salva modifiche
                            </button>
                            <a href="{{ route('items.show', $item) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                                Annulla
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
    function itemForm(collectionsData, initialCollectionId) {
        return {
            selectedCollectionId: String(initialCollectionId || ''),
            collectionsData: collectionsData,
            savedValues: @json(old() ?: $currentAttrValues),
            get currentAttributes() {
                if (!this.selectedCollectionId) return [];
                return this.collectionsData[this.selectedCollectionId]?.attributes || [];
            },
            typeLabel(type) {
                const labels = { text: 'Testo', number: 'Numero', date: 'Data', boolean: 'Sì/No', url: 'URL' };
                return labels[type] || type;
            }
        }
    }
    </script>
</x-app-layout>
