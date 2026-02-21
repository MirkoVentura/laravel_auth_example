<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">📍 {{ $location->name }}</h2>
                @if ($location->description)
                    <p class="text-gray-500 text-sm mt-1">{{ $location->description }}</p>
                @endif
            </div>
            <a href="{{ route('locations.edit', $location) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium">
                Modifica
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <h3 class="text-lg font-medium text-gray-700 mb-4">Oggetti in questo luogo ({{ $items->count() }})</h3>

            @forelse ($items as $item)
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-3 flex justify-between items-center">
                    <div>
                        <a href="{{ route('items.show', $item) }}" class="font-medium text-gray-800 hover:text-indigo-600">
                            {{ $item->name }}
                        </a>
                        <div class="text-sm text-gray-500 mt-1">
                            <a href="{{ route('collections.show', $item->collection) }}" class="hover:text-indigo-600">
                                {{ $item->collection->name }}
                            </a>
                            @if ($item->quantity > 1)
                                &bull; x{{ $item->quantity }}
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
                    <a href="{{ route('items.edit', $item) }}" class="text-gray-500 hover:underline text-sm ml-4">Modifica</a>
                </div>
            @empty
                <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-10 text-center">
                    <p class="text-gray-400">Nessun oggetto in questo luogo.</p>
                </div>
            @endforelse

            <div class="mt-4">
                <a href="{{ route('locations.index') }}" class="text-amber-600 hover:underline text-sm">← Tutti i luoghi</a>
            </div>
        </div>
    </div>
</x-app-layout>
