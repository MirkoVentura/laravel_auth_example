<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Collezioni</h2>
            <a href="{{ route('collections.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                + Nuova collezione
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            @forelse ($collections as $collection)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-4 flex justify-between items-center">
                    <div>
                        <a href="{{ route('collections.show', $collection) }}" class="text-lg font-semibold text-gray-800 hover:text-indigo-600">
                            {{ $collection->name }}
                        </a>
                        @if ($collection->description)
                            <p class="text-gray-500 text-sm mt-1">{{ $collection->description }}</p>
                        @endif
                        <p class="text-gray-400 text-xs mt-1">{{ $collection->items_count }} oggetti</p>
                    </div>
                    <div class="flex gap-3 ml-4">
                        <a href="{{ route('collections.show', $collection) }}" class="text-indigo-600 hover:underline text-sm">Visualizza</a>
                        <a href="{{ route('collections.edit', $collection) }}" class="text-gray-500 hover:underline text-sm">Modifica</a>
                        <form method="POST" action="{{ route('collections.destroy', $collection) }}" onsubmit="return confirm('Eliminare questa collezione e tutti i suoi oggetti?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                    <p class="text-gray-500 mb-4">Nessuna collezione ancora.</p>
                    <a href="{{ route('collections.create') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 text-sm font-medium">
                        Crea la prima collezione
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
