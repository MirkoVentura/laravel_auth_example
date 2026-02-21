<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $collection->name }}</h2>
                @if ($collection->description)
                    <p class="text-gray-500 text-sm mt-1">{{ $collection->description }}</p>
                @endif
            </div>
            <div class="flex gap-3">
                <a href="{{ route('items.create', ['collection_id' => $collection->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                    + Aggiungi oggetto
                </a>
                <a href="{{ route('collections.edit', $collection) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium">
                    Modifica
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            @include('partials.flash')

            {{-- SEZIONE CARATTERISTICHE --}}
            <div x-data="{ open: {{ $collection->attributes->isEmpty() ? 'true' : 'false' }} }" class="bg-white rounded-lg shadow-sm border border-gray-200">
                <button @click="open = !open" class="w-full flex justify-between items-center px-5 py-4 text-left">
                    <span class="font-semibold text-gray-700">
                        Caratteristiche configurate
                        <span class="text-sm font-normal text-gray-400 ml-2">
                            ({{ $collection->attributes->count() }} definite &mdash; compaiono nel form di ogni oggetto)
                        </span>
                    </span>
                    <svg :class="open ? 'rotate-180' : ''" class="w-5 h-5 text-gray-400 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <div x-show="open" x-transition class="px-5 pb-5 border-t border-gray-100">
                    @forelse ($collection->attributes as $attr)
                        <div class="flex items-center justify-between py-3 border-b border-gray-50 last:border-0 group">
                            <div class="flex items-center gap-3">
                                <span class="text-xs px-2 py-0.5 rounded-full bg-gray-100 text-gray-600 font-mono">{{ \App\Models\CollectionAttribute::TYPES[$attr->type] }}</span>
                                <span class="font-medium text-gray-800">{{ $attr->name }}</span>
                                @if ($attr->required)<span class="text-xs text-red-500">obbligatorio</span>@endif
                            </div>
                            <div class="flex items-center gap-3 opacity-0 group-hover:opacity-100 transition-opacity">
                                <button onclick="document.getElementById('edit-attr-{{ $attr->id }}').classList.toggle('hidden')"
                                    class="text-xs text-gray-500 hover:text-indigo-600">Modifica</button>
                                <form method="POST" action="{{ route('collection-attributes.destroy', [$collection, $attr]) }}"
                                    onsubmit="return confirm('Eliminare questa caratteristica? I valori salvati verranno persi.')">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="text-xs text-red-500 hover:underline">Elimina</button>
                                </form>
                            </div>
                        </div>
                        <div id="edit-attr-{{ $attr->id }}" class="hidden bg-gray-50 rounded-lg p-4 mb-2">
                            <form method="POST" action="{{ route('collection-attributes.update', [$collection, $attr]) }}" class="flex flex-wrap gap-3 items-end">
                                @csrf @method('PATCH')
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Nome</label>
                                    <input type="text" name="name" value="{{ $attr->name }}" required
                                        class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                </div>
                                <div>
                                    <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                                    <select name="type" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                        @foreach (\App\Models\CollectionAttribute::TYPES as $val => $label)
                                            <option value="{{ $val }}" {{ $attr->type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="flex items-center gap-2">
                                    <input type="checkbox" name="required" id="req-{{ $attr->id }}" value="1" {{ $attr->required ? 'checked' : '' }} class="rounded border-gray-300">
                                    <label for="req-{{ $attr->id }}" class="text-sm text-gray-600">Obbligatorio</label>
                                </div>
                                <button type="submit" class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700">Salva</button>
                                <button type="button" onclick="document.getElementById('edit-attr-{{ $attr->id }}').classList.add('hidden')"
                                    class="text-sm text-gray-500 hover:underline">Annulla</button>
                            </form>
                        </div>
                    @empty
                        <p class="text-gray-400 text-sm py-3">Nessuna caratteristica definita.</p>
                    @endforelse

                    <div class="mt-4 pt-4 border-t border-gray-100">
                        <p class="text-xs font-semibold text-gray-500 uppercase tracking-wide mb-3">Aggiungi caratteristica</p>
                        <form method="POST" action="{{ route('collection-attributes.store', $collection) }}" class="flex flex-wrap gap-3 items-end">
                            @csrf
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Nome *</label>
                                <input type="text" name="name" placeholder="Es. Prezzo, Editore, Valutazione..." required
                                    class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500 w-56">
                            </div>
                            <div>
                                <label class="block text-xs text-gray-500 mb-1">Tipo</label>
                                <select name="type" class="rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach (\App\Models\CollectionAttribute::TYPES as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex items-center gap-2">
                                <input type="checkbox" name="required" id="new-req" value="1" class="rounded border-gray-300">
                                <label for="new-req" class="text-sm text-gray-600">Obbligatorio</label>
                            </div>
                            <button type="submit" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 font-medium">+ Aggiungi</button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- LISTA OGGETTI --}}
            <div>
                @forelse ($items as $item)
                    @php $attrMap = $item->itemAttributes->keyBy(fn($a) => $a->definition?->key); @endphp
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3 flex justify-between items-start">
                        <div class="flex-1">
                            <a href="{{ route('items.show', $item) }}" class="font-medium text-gray-800 hover:text-indigo-600">{{ $item->name }}</a>
                            <div class="flex flex-wrap gap-x-4 gap-y-1 mt-1 text-sm text-gray-500">
                                @if ($item->location)<span>📍 {{ $item->location->name }}</span>@endif
                                @if ($item->quantity > 1)<span>x{{ $item->quantity }}</span>@endif
                                @foreach ($collection->attributes as $attr)
                                    @if ($attrMap->has($attr->key) && $attrMap[$attr->key]->value !== null && $attrMap[$attr->key]->value !== '')
                                        <span>
                                            <span class="text-gray-400 text-xs">{{ $attr->name }}:</span>
                                            @if ($attr->type === 'boolean'){{ $attrMap[$attr->key]->value ? 'Sì' : 'No' }}
                                            @elseif ($attr->type === 'url')<a href="{{ $attrMap[$attr->key]->value }}" target="_blank" class="text-indigo-500 hover:underline text-xs">link</a>
                                            @else{{ $attrMap[$attr->key]->value }}@endif
                                        </span>
                                    @endif
                                @endforeach
                            </div>
                            @if ($item->tags->isNotEmpty())
                                <div class="flex gap-1 mt-2 flex-wrap">
                                    @foreach ($item->tags as $tag)
                                        <span class="px-2 py-0.5 rounded-full text-xs text-white font-medium" style="background-color: {{ $tag->color }}">{{ $tag->name }}</span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="flex gap-3 ml-4 flex-shrink-0">
                            <a href="{{ route('items.edit', $item) }}" class="text-gray-500 hover:underline text-sm">Modifica</a>
                            <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Eliminare questo oggetto?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-red-500 hover:underline text-sm">Elimina</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                        <p class="text-gray-500 mb-4">Nessun oggetto in questa collezione.</p>
                        <a href="{{ route('items.create', ['collection_id' => $collection->id]) }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">Aggiungi il primo oggetto</a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
