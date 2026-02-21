<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">Nuova collezione</h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
                <form method="POST" action="{{ route('collections.store') }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nome *</label>
                        <input type="text" name="name" value="{{ old('name') }}" required
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Es. Libri, Utensili, Giochi...">
                        @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Descrizione</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"
                            placeholder="Descrizione opzionale...">{{ old('description') }}</textarea>
                        @error('description') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                    </div>

                    <div class="flex gap-3">
                        <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 font-medium">
                            Crea collezione
                        </button>
                        <a href="{{ route('collections.index') }}" class="px-6 py-2 border border-gray-300 rounded-lg text-gray-600 hover:bg-gray-50 font-medium">
                            Annulla
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
