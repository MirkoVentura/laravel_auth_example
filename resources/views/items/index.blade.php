<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Tutti gli oggetti</h2>
            <a href="{{ route('items.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                + Nuovo oggetto
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            @forelse ($items as $item)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3 flex justify-between items-start">
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
                    <div class="flex gap-3 ml-4">
                        <a href="{{ route('items.edit', $item) }}" class="text-gray-500 hover:underline text-sm">Modifica</a>
                        <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Eliminare questo oggetto?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                    <p class="text-gray-500 mb-4">Nessun oggetto ancora.</p>
                    <a href="{{ route('items.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                        Aggiungi il primo oggetto
                    </a>
                </div>
            @endforelse

            <div class="mt-4">{{ $items->links() }}</div>
        </div>
    </div>
</x-app-layout>
