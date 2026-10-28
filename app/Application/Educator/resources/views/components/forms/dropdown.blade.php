@props([
    'label' => null,
    'placeholder' => 'اختر من القائمة',
    'options' => [],
    'optionValue' => 'id',
    'optionLabel' => 'name',
    'model' => '',
    'disabled' => false,
    'customOption' => null,
    'isArray' => false,
])

@php
    // نجيب القيمة المختارة من الـ Livewire property باستخدام eval (لأن model اسم المتغير)
    $selectedValue = data_get($attributes->get('wire:model') ? $attributes->get('wire:model')->value() : null, '') ?: $model;

    // ابحث عن العنصر اللي يطابق قيمة الـ model
    $selectedOption = null;
    foreach ($options as $option) {
        $value = $isArray ? ($option[$optionValue] ?? null) : ($option->{$optionValue} ?? null);
        if ($value == $selectedValue) {
            $selectedOption = $option;
            break;
        }
    }

    // اسم العرض
    if ($selectedOption) {
        $selectedLabel = $isArray ? ($selectedOption[$optionLabel] ?? '') : ($selectedOption->{$optionLabel} ?? '');
    } elseif ($customOption && $customOption['value'] == $selectedValue) {
        $selectedLabel = $customOption['label'];
    } else {
        $selectedLabel = null;
    }
@endphp

<div class="space-y-1">
    @if ($label)
        <label class="form-label">{{ $label }}</label>
    @endif

    <div x-data="{ open: false }" class="relative">
        <button
            type="button"
            @click="open = !open"
            :disabled="{{ $disabled ? 'true' : 'false' }}"
            class="w-full form-input bg-white flex items-center justify-between disabled:bg-gray-100 disabled:cursor-not-allowed"
            :class="{ 'text-right': '{{ app()->getLocale() }}' === 'ar', 'text-left': '{{ app()->getLocale() }}' !== 'ar' }"
        >
            <span class="truncate">{{ $selectedLabel ?: $placeholder }}</span>
            <svg class="w-4 h-4 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none"
                 viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
            </svg>
        </button>

        <div
            x-show="open"
            @click.away="open = false"
            x-cloak
            class="absolute z-20 w-full mt-1 bg-white border border-gray-200 rounded-lg shadow-lg max-h-60 overflow-y-auto"
        >
            @foreach($options as $option)
                @php
                    $value = $isArray ? ($option[$optionValue] ?? null) : ($option->{$optionValue} ?? null);
                    $label = $isArray ? ($option[$optionLabel] ?? '') : ($option->{$optionLabel} ?? '');
                @endphp

                <div
                    wire:click="$set('{{ $model }}', '{{ $value }}')"
                    @click="open = false"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer"
                >
                    {{ $label }}
                </div>
            @endforeach

            @if ($customOption)
                <div
                    wire:click="$set('{{ $model }}', '{{ $customOption['value'] }}')"
                    @click="open = false"
                    class="px-4 py-2 hover:bg-gray-100 cursor-pointer text-blue-600 font-medium"
                >
                    {{ $customOption['label'] }}
                </div>
            @endif
        </div>
    </div>

    @error($model) <span class="form-error">{{ $message }}</span> @enderror
</div>
