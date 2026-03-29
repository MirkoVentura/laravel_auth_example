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
