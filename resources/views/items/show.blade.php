<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">{{ $item->name }}</h2>
            <div class="flex gap-3">
                <a href="{{ route('items.edit', $item) }}" class="px-4 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 text-sm font-medium">
                    Modifica
                </a>
                <form method="POST" action="{{ route('items.destroy', $item) }}" onsubmit="return confirm('Eliminare questo oggetto?')">
                    @csrf @method('DELETE')
                    <button type="submit" class="px-4 py-2 border border-red-300 text-red-600 rounded-lg hover:bg-red-50 text-sm font-medium">
                        Elimina
                    </button>
                </form>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            @include('partials.flash')

            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                @if ($item->description)
                    <p class="text-gray-600 mb-6">{{ $item->description }}</p>
                @endif

                <dl class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <dt class="text-sm font-medium text-gray-500">Collezione</dt>
                        <dd class="mt-1">
                            <a href="{{ route('collections.show', $item->collection) }}" class="text-indigo-600 hover:underline font-medium">
                                {{ $item->collection->name }}
                            </a>
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Luogo</dt>
                        <dd class="mt-1">
                            @if ($item->location)
                                <a href="{{ route('locations.show', $item->location) }}" class="text-indigo-600 hover:underline font-medium">
                                    📍 {{ $item->location->name }}
                                </a>
                            @else
                                <span class="text-gray-400">Non specificato</span>
                            @endif
                        </dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Quantità</dt>
                        <dd class="mt-1 font-medium">{{ $item->quantity }}</dd>
                    </div>

                    <div>
                        <dt class="text-sm font-medium text-gray-500">Aggiunto il</dt>
                        <dd class="mt-1 text-gray-600">{{ $item->created_at->format('d/m/Y') }}</dd>
                    </div>
                </dl>

                @if ($item->tags->isNotEmpty())
                    <div class="mt-6">
                        <dt class="text-sm font-medium text-gray-500 mb-2">Etichette</dt>
                        <div class="flex flex-wrap gap-2">
                            @foreach ($item->tags as $tag)
                                <a href="{{ route('tags.show', $tag) }}" class="px-3 py-1 rounded-full text-sm text-white font-medium hover:opacity-80" style="background-color: {{ $tag->color }}">
                                    {{ $tag->name }}
                                </a>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <div class="mt-4">
                <a href="{{ route('collections.show', $item->collection) }}" class="text-indigo-600 hover:underline text-sm">
                    ← Torna alla collezione {{ $item->collection->name }}
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
