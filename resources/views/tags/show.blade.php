<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-3">
                <span class="w-6 h-6 rounded-full" style="background-color: {{ $tag->color }}"></span>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $tag->name }}</h2>
            </div>
            <a href="{{ route('tags.edit', $tag) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium">
                Modifica
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h3 class="text-lg font-medium text-gray-700 mb-4">Oggetti con questa etichetta ({{ $items->count() }})</h3>

            @forelse ($items as $item)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3 flex justify-between items-center">
                    <div>
                        <a href="{{ route('items.show', $item) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                            {{ $item->name }}
                        </a>
                        <div class="flex gap-4 mt-1 text-sm text-gray-500">
                            <a href="{{ route('collections.show', $item->collection) }}" class="hover:text-indigo-600">
                                {{ $item->collection->name }}
                            </a>
                            @if ($item->location)
                                <span>📍 {{ $item->location->name }}</span>
                            @endif
                        </div>
                    </div>
                    <a href="{{ route('items.edit', $item) }}" class="text-gray-500 hover:underline text-sm ml-4">Modifica</a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                    <p class="text-gray-400">Nessun oggetto con questa etichetta.</p>
                </div>
            @endforelse

            <div class="mt-4">
                <a href="{{ route('tags.index') }}" class="text-rose-600 hover:underline text-sm">← Tutte le etichette</a>
            </div>
        </div>
    </div>
</x-app-layout>
