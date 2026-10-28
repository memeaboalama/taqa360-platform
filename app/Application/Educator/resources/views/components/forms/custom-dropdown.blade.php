@props([
    'wireModel' => null,
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'placeholder' => 'Select an option',
    'searchable' => false,
    'noResultsText' => 'No results found',
    'customOptions' => [],
])

@php
    $allOptions = array_merge($options, $customOptions);
@endphp

<div x-data="dropdown({
        value: @entangle($wireModel),
        options: @js($allOptions),
        optionValue: '{{ $optionValue }}',
        optionLabel: '{{ $optionLabel }}',
        placeholder: '{{ $placeholder }}',
        searchable: {{ $searchable ? 'true' : 'false' }},
        noResultsText: '{{ $noResultsText }}'
    })"
    class="relative"
    x-id="['dropdown']"
>
    <button
        type="button"
        @click="open = !open"
        :aria-expanded="open"
        :aria-controls="$id('dropdown-button')"
        class="w-full form-input flex items-center justify-between text-right bg-white pr-3"
    >
        <span class="truncate text-left" x-text="displayLabel || placeholder"></span>
        <svg class="w-4 h-4 text-gray-400 transition-transform duration-200"
            :class="{ 'rotate-180': open }"
            xmlns="http://www.w3.org/2000/svg"
            fill="none"
            viewBox="0 0 24 24"
            stroke-width="1.5"
            stroke="currentColor"
        >
            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
        </svg>
    </button>

    <div
        x-show="open"
        @click.away="open = false"
        x-cloak
        :id="$id('dropdown-button')"
        class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg overflow-hidden"
        x-transition:enter="transition ease-out duration-100"
        x-transition:enter-start="transform opacity-0 scale-95"
        x-transition:enter-end="transform opacity-100 scale-100"
        x-transition:leave="transition ease-in duration-75"
        x-transition:leave-start="transform opacity-100 scale-100"
        x-transition:leave-end="transform opacity-0 scale-95"
    >
        <template x-if="searchable">
            <div class="sticky top-0 p-2 bg-white border-b">
                <input
                    x-model="searchQuery"
                    type="text"
                    placeholder="{{ __('educator::builder.academic_info.search_placeholder') }}"
                    class="w-full px-3 py-2 text-sm border border-gray-200 rounded focus:ring-primary focus:border-primary"
                    @click.stop
                >
            </div>
        </template>

        <div class="max-h-60 overflow-y-auto">
            <template x-for="option in filteredOptions" :key="option[optionValue]">
                <div
                    @click="selectOption(option[optionValue])"
                    class="dropdown-item px-4 py-2 hover:bg-gray-50 cursor-pointer transition-colors"
                    :class="{
                        'bg-primary/10 text-primary': value == option[optionValue],
                        'text-gray-700': value != option[optionValue]
                    }"
                    x-text="option[optionLabel]"
                ></div>
            </template>

            <template x-if="filteredOptions.length === 0">
                <div class="px-4 py-3 text-sm text-center text-gray-500" x-text="noResultsText"></div>
            </template>
        </div>
    </div>
</div>

<script>
    document.addEventListener('alpine:init', () => {
        Alpine.data('dropdown', (config) => ({
            open: false,
            value: config.value,
            options: config.options,
            searchQuery: '',
            optionValue: config.optionValue,
            optionLabel: config.optionLabel,
            placeholder: config.placeholder,
            searchable: config.searchable,
            noResultsText: config.noResultsText,

            get displayLabel() {
                if (!this.value) return null;
                const selected = this.options.find(
                    option => String(option[this.optionValue]) === String(this.value)
                );
                return selected ? selected[this.optionLabel] : null;
            },

            get filteredOptions() {
                if (!this.searchable || !this.searchQuery) {
                    return this.options;
                }

                const query = this.searchQuery.toLowerCase();
                return this.options.filter(option =>
                    String(option[this.optionLabel]).toLowerCase().includes(query)
                );
            },

            selectOption(optionValue) {
                this.value = optionValue;
                this.open = false;
                this.searchQuery = '';
            }
        }));
    });
</script>

<style>
    .dropdown-item {
        transition: background-color 0.2s ease;
    }

    .radio-input:checked + .radio-label {
        font-weight: 600;
        color: #306FAA;
    }

    [x-cloak] {
        display: none !important;
    }
</style>
