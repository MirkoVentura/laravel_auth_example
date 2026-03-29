<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Luoghi</h2>
            <a href="{{ route('locations.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm font-medium">
                + Nuovo luogo
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            @forelse ($locations as $location)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-5 mb-4 flex justify-between items-center">
                    <div>
                        <a href="{{ route('locations.show', $location) }}" class="text-lg font-semibold text-gray-800 hover:text-amber-600">
                            📍 {{ $location->name }}
                        </a>
                        @if ($location->description)
                            <p class="text-gray-500 text-sm mt-1">{{ $location->description }}</p>
                        @endif
                        <p class="text-gray-400 text-xs mt-1">{{ $location->items_count }} oggetti qui</p>
                    </div>
                    <div class="flex gap-3 ml-4">
                        <a href="{{ route('locations.show', $location) }}" class="text-amber-600 hover:underline text-sm">Visualizza</a>
                        <a href="{{ route('locations.edit', $location) }}" class="text-gray-500 hover:underline text-sm">Modifica</a>
                        <form method="POST" action="{{ route('locations.destroy', $location) }}" onsubmit="return confirm('Eliminare questo luogo?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="text-red-500 hover:underline text-sm">Elimina</button>
                        </form>
                    </div>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                    <p class="text-gray-500 mb-4">Nessun luogo ancora.</p>
                    <a href="{{ route('locations.create') }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 text-sm font-medium">
                        Crea il primo luogo
                    </a>
                </div>
            @endforelse
        </div>
    </div>
</x-app-layout>
