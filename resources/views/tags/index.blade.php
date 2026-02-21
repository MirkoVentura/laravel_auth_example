<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Etichette</h2>
            <a href="{{ route('tags.create') }}" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 text-sm font-medium">
                + Nuova etichetta
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="flex flex-wrap gap-4">
                @forelse ($tags as $tag)
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 flex items-center gap-4 min-w-[200px]">
                        <a href="{{ route('tags.show', $tag) }}" class="flex items-center gap-3 flex-1">
                            <span class="w-8 h-8 rounded-full flex-shrink-0" style="background-color: {{ $tag->color }}"></span>
                            <div>
                                <div class="font-medium text-gray-800 hover:text-rose-600">{{ $tag->name }}</div>
                                <div class="text-xs text-gray-400">{{ $tag->items_count }} oggetti</div>
                            </div>
                        </a>
                        <div class="flex gap-2">
                            <a href="{{ route('tags.edit', $tag) }}" class="text-gray-400 hover:text-gray-600 text-sm">✏️</a>
                            <form method="POST" action="{{ route('tags.destroy', $tag) }}" onsubmit="return confirm('Eliminare questa etichetta?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="text-gray-400 hover:text-red-500 text-sm">🗑️</button>
                            </form>
                        </div>
                    </div>
                @empty
                    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center w-full">
                        <p class="text-gray-500 mb-4">Nessuna etichetta ancora.</p>
                        <a href="{{ route('tags.create') }}" class="px-4 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 text-sm font-medium">
                            Crea la prima etichetta
                        </a>
                    </div>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
