{{--
  Partial: campi dinamici per gli attributi della collezione.
  $collectionAttributes  = Collection::attributes (ordinata)
  $attributeMap          = Item::attributeMap (opzionale, per la edit)
--}}
@if ($collectionAttributes->isNotEmpty())
    <div class="border-t border-gray-100 mt-4 pt-4">
        <h4 class="text-sm font-semibold text-gray-600 mb-3 uppercase tracking-wide">Caratteristiche</h4>
        <div class="space-y-4">
            @foreach ($collectionAttributes as $attr)
                @php
                    $fieldName  = "attr_{$attr->key}";
                    $currentVal = old($fieldName, $attributeMap[$attr->key]?->value ?? null);
                @endphp

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">
                        {{ $attr->name }}
                        @if ($attr->required) <span class="text-red-500">*</span> @endif
                        <span class="text-xs text-gray-400 font-normal ml-1">({{ \App\Models\CollectionAttribute::TYPES[$attr->type] }})</span>
                    </label>

                    @if ($attr->type === 'text')
                        <input type="text" name="{{ $fieldName }}" value="{{ $currentVal }}"
                            {{ $attr->required ? 'required' : '' }}
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                    @elseif ($attr->type === 'number')
                        <input type="number" name="{{ $fieldName }}" value="{{ $currentVal }}" step="any"
                            {{ $attr->required ? 'required' : '' }}
                            class="w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                    @elseif ($attr->type === 'date')
                        <input type="date" name="{{ $fieldName }}" value="{{ $currentVal }}"
                            {{ $attr->required ? 'required' : '' }}
                            class="w-48 rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">

                    @elseif ($attr->type === 'boolean')
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="{{ $fieldName }}" value="1"
                                {{ $currentVal ? 'checked' : '' }}
                                class="rounded border-gray-300 text-indigo-600">
                            <span class="text-sm text-gray-600">Sì</span>
                        </label>

                    @elseif ($attr->type === 'url')
                        <input type="url" name="{{ $fieldName }}" value="{{ $currentVal }}"
                            {{ $attr->required ? 'required' : '' }}
                            placeholder="https://..."
                            class="w-full rounded-lg border-gray-300 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 text-sm">
                    @endif

                    @error($fieldName)
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                    @enderror
                </div>
            @endforeach
        </div>
    </div>
@endif
