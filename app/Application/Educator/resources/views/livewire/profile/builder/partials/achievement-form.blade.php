<div class="space-y-4">
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.achievements.entry_type') *</label>
        <x-educator::forms.select wire:model="formData.type" :options="collect($achievementTypes)->map(fn($label, $value) => ['id' => $value, 'name' => $label])->values()" :is-array="true" option-value="id" option-label="name" placeholder="{{ __('educator::builder.achievements.select_entry_type') }}" :selected-label="$achievementTypes[$formData['type'] ?? ''] ?? ''" />
        @error('formData.type') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
            <label class="form-label">{{ ($formData['type'] ?? '') === 'work_experience' ? __('educator::builder.achievements.job_title') : __('educator::builder.achievements.title') }} *</label>
            <input wire:model.defer="formData.title" type="text" class="form-input">
            @error('formData.title') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        <div class="space-y-1">
            <label class="form-label">{{ ($formData['type'] ?? '') === 'work_experience' ? __('educator::builder.achievements.company') : __('educator::builder.achievements.issuing_organization') }} *</label>
            <input wire:model.defer="formData.organization" type="text" class="form-input">
            @error('formData.organization') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="space-y-1">
            <label class="form-label">{{ ($formData['type'] ?? '') === 'work_experience' ? __('educator::builder.achievements.start_date') : __('educator::builder.achievements.issue_date') }} *</label>
            <input wire:model.defer="formData.start_date" type="date" class="form-input">
            @error('formData.start_date') <span class="form-error">{{ $message }}</span> @enderror
        </div>
        @if(($formData['type'] ?? '') == 'work_experience')
            <div class="space-y-1">
                <label class="form-label">@lang('educator::builder.achievements.end_date')</label>
                <input wire:model.defer="formData.end_date" type="date" class="form-input">
                @error('formData.end_date') <span class="form-error">{{ $message }}</span> @enderror
            </div>
        @endif
    </div>
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.achievements.description')</label>
        <textarea wire:model.defer="formData.description" rows="3" class="form-input"></textarea>
    </div>
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.achievements.attachment')</label>
        @include('educator::livewire.profile.builder.partials.image-uploader', ['model' => 'upload', 'existing' => $existingFile, 'collection' => 'achievement_attachment'])
    </div>
    <div class="flex justify-between items-center pt-4 border-t">
        <div>
            @if($activeId)
                <button wire:click="confirmRemoval({{ $activeId }})" type="button" class="text-sm font-medium text-red-600 hover:underline">@lang('educator::builder.achievements.delete_confirmation')</button>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <button wire:click="closeForm" type="button" class="btn-secondary">@lang('educator::builder.cancel')</button>
            <button wire:click="save" wire:loading.attr="disabled" wire:target="save" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <svg wire:loading wire:target="save" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <span>{{ $activeId ? __('educator::builder.save_changes') : __('educator::builder.achievements.add_achievement') }}</span>
            </button>
        </div>
    </div>
</div>
