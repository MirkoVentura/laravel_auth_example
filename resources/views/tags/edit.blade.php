<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Modifica etichetta</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('tags.update', $tag) }}">
                    @csrf @method('PATCH')

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="name" value="{{ old('name', $tag->name) }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-rose-500 focus:ring-rose-500">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Colore</label>
                        <div class="flex items-center gap-3">
                            <input type="color" name="color" value="{{ old('color', $tag->color) }}"
                                class="w-12 h-10 rounded border-gray-300 cursor-pointer">
                            <span class="text-sm text-gray-500">Colore attuale dell'etichetta</span>
                        </div>
                        @error('color') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-rose-600 text-white rounded-lg hover:bg-rose-700 font-medium">
                            Salva modifiche
                        </button>
                        <a href="{{ route('tags.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
