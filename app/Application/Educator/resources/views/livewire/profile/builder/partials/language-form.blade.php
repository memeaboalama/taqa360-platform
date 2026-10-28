<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.languages.language') *</label>
        <x-educator::forms.select
            wire:model="formData.language_id"
            :options="$languageOptions"
            option-value="id"
            option-label="title"
            :is-array="true"
            placeholder="{{ __('educator::builder.languages.select_language') }}"
            :selected-label="collect($languageOptions)->firstWhere('id', $formData['language_id'] ?? null)['title'] ?? ''"
        />
        @error('formData.language_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.languages.level') *</label>
        <x-educator::forms.select
            wire:model="formData.level"
            :options="collect($levelOptions)->map(fn($label, $value) => ['id' => $value, 'title' => $label])->values()"
            option-value="id"
            option-label="title"
            :is-array="true"
            placeholder="{{ __('educator::builder.languages.select_level') }}"
            :selected-label="$levelOptions[$formData['level'] ?? null] ?? ''"
        />
        @error('formData.level') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="md:col-span-2 flex items-center pt-2">
        <label class="flex items-center cursor-pointer">
            <input wire:model="formData.is_primary" type="checkbox" class="radio-input">
            <span class="radio-label">@lang('educator::builder.languages.is_primary')</span>
        </label>
    </div>
</div>
