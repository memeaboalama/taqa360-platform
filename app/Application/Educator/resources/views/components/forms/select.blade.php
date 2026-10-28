@props([
    'placeholder' => 'Select an option',
    'options' => [], // Expects a Collection of objects or an array of arrays
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'selectedLabel' => '',
    'disabled' => false,
    'isArray' => false,
])

<div x-data="{ open: false }" class="relative">
    <button
        type="button"
        @click="open = !open"
        :disabled="{{ $disabled ? 'true' : 'false' }}"
        class="w-full form-input flex items-center justify-between {{ app()->getLocale() === 'ar' ? 'text-right' : 'text-left' }} bg-white disabled:bg-gray-100 disabled:cursor-not-allowed"
    >
        <span class="truncate">{{ $selectedLabel ?: $placeholder }}</span>
        <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" /></svg>
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-cloak
        class="absolute z-10 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
    >
        @if ($isArray)
            @foreach($options as $option)
                <div
                    wire:click="$set('{{ $attributes->wire('model')->value() }}', '{{ $option[$optionValue] }}')"
                    @click="open = false"
                    class="dropdown-item"
                >
                    {{ $option[$optionLabel] }}
                </div>
            @endforeach
        @else
            @foreach($options as $option)
                <div
                    wire:click="$set('{{ $attributes->wire('model')->value() }}', '{{ $option->{$optionValue} }}')"
                    @click="open = false"
                    class="dropdown-item"
                >
                    {{ $option->{$optionLabel} }}
                </div>
            @endforeach
        @endif

        {{ $slot }}
    </div>
</div>
