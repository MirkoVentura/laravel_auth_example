<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifica collezione</h2>
            <a href="{{ route('collections.show', $collection) }}" class="text-sm text-indigo-600 hover:underline">
                ← Vai alla collezione
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8 space-y-8">
            @include('partials.flash')

            {{-- SEZIONE 1: Nome e descrizione --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Informazioni generali</h3>
                </div>
                <div class="px-6 py-6">
                    <form method="POST" action="{{ route('collections.update', $collection) }}">
                        @csrf @method('PATCH')

                        <div class="mb-5">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                            <input type="text" name="name" value="{{ old('name', $collection->name) }}" required
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                            @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="mb-6">
                            <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                            <textarea name="description" rows="3"
                                class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">{{ old('description', $collection->description) }}</textarea>
                            @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                        </div>

                        <div class="flex gap-3">
                            <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                                Salva modifiche
                            </button>
                            <a href="{{ route('collections.show', $collection) }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                                Annulla
                            </a>
                        </div>
                    </form>
                </div>
            </div>

            {{-- SEZIONE 2: Caratteristiche personalizzate --}}
            <div class="bg-white rounded-lg shadow-sm border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-100">
                    <h3 class="font-semibold text-gray-800">Caratteristiche personalizzate</h3>
                    <p class="text-sm text-gray-500 mt-1">
                        Definisci i campi aggiuntivi che appariranno nel form di ogni oggetto di questa collezione.
                    </p>
                </div>

                {{-- Lista caratteristiche esistenti --}}
                @if ($collection->attributes->isNotEmpty())
                    <ul class="divide-y divide-gray-100">
                        @foreach ($collection->attributes as $attr)
                            <li class="px-6 py-4">
                                {{-- Riga di visualizzazione --}}
                                <div class="flex items-center justify-between" id="view-{{ $attr->id }}">
                                    <div class="flex items-center gap-3">
                                        <span class="text-xs px-2 py-1 rounded-full bg-gray-100 text-gray-600 font-mono font-medium">
                                            {{ \App\Models\CollectionAttribute::TYPES[$attr->type] }}
                                        </span>
                                        <span class="font-medium text-gray-800">{{ $attr->name }}</span>
                                        @if ($attr->required)
                                            <span class="text-xs text-red-500 font-medium">obbligatorio</span>
                                        @endif
                                    </div>
                                    <div class="flex items-center gap-4">
                                        <button type="button"
                                            onclick="toggleEdit({{ $attr->id }})"
                                            class="text-sm text-indigo-600 hover:underline font-medium">
                                            Modifica
                                        </button>
                                        <form method="POST" action="{{ route('collection-attributes.destroy', [$collection, $attr]) }}"
                                            onsubmit="return confirm('Eliminare questa caratteristica? I valori salvati negli oggetti verranno persi.')">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="text-sm text-red-500 hover:underline font-medium">Elimina</button>
                                        </form>
                                    </div>
                                </div>

                                {{-- Form modifica inline --}}
                                <div id="edit-{{ $attr->id }}" class="hidden mt-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                                    <form method="POST" action="{{ route('collection-attributes.update', [$collection, $attr]) }}">
                                        @csrf @method('PATCH')
                                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome</label>
                                                <input type="text" name="name" value="{{ $attr->name }}" required
                                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            </div>
                                            <div>
                                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo</label>
                                                <select name="type" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                    @foreach (\App\Models\CollectionAttribute::TYPES as $val => $label)
                                                        <option value="{{ $val }}" {{ $attr->type === $val ? 'selected' : '' }}>{{ $label }}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="flex items-center justify-between">
                                            <label class="flex items-center gap-2 cursor-pointer">
                                                <input type="checkbox" name="required" value="1" {{ $attr->required ? 'checked' : '' }}
                                                    class="rounded border-gray-300 text-indigo-600">
                                                <span class="text-sm text-gray-600">Campo obbligatorio</span>
                                            </label>
                                            <div class="flex gap-3">
                                                <button type="button" onclick="toggleEdit({{ $attr->id }})"
                                                    class="px-4 py-2 border border-gray-300 rounded-lg text-sm text-gray-600 hover:bg-gray-100">
                                                    Annulla
                                                </button>
                                                <button type="submit"
                                                    class="px-4 py-2 bg-indigo-600 text-white rounded-lg text-sm hover:bg-indigo-700 font-medium">
                                                    Salva
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <div class="px-6 py-6 text-sm text-gray-400">
                        Nessuna caratteristica definita. Aggiungine una qui sotto.
                    </div>
                @endif

                {{-- Form aggiungi nuova caratteristica --}}
                <div class="px-6 py-6 border-t border-gray-100 bg-gray-50 rounded-b-lg">
                    <h4 class="text-sm font-semibold text-gray-700 mb-4">Aggiungi caratteristica</h4>
                    <form method="POST" action="{{ route('collection-attributes.store', $collection) }}"
                        onkeydown="return event.key !== 'Enter'">
                        @csrf
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 mb-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                                <input type="text" name="name" placeholder="Es. Prezzo, Editore, Data acquisto…"
                                    class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700 mb-1">Tipo valore</label>
                                <select name="type" class="w-full rounded-lg border-gray-300 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                                    @foreach (\App\Models\CollectionAttribute::TYPES as $val => $label)
                                        <option value="{{ $val }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="flex items-center justify-between">
                            <label class="flex items-center gap-2 cursor-pointer">
                                <input type="checkbox" name="required" value="1" class="rounded border-gray-300 text-indigo-600">
                                <span class="text-sm text-gray-600">Campo obbligatorio</span>
                            </label>
                            <button type="submit"
                                class="px-5 py-2 bg-emerald-600 text-white rounded-lg text-sm hover:bg-emerald-700 font-medium">
                                + Aggiungi
                            </button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
    function toggleEdit(id) {
        const el = document.getElementById('edit-' + id);
        el.classList.toggle('hidden');
    }
    </script>
</x-app-layout>
