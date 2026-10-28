<div class="grid grid-cols-1 md:grid-cols-2 gap-x-6 gap-y-4">
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.academic_info.education_level') *</label>
        <x-educator::forms.select wire:model="formData.degree" :options="$educationLevels" :is-array="true" option-value="id" option-label="name" placeholder="{{ __('educator::builder.academic_info.select_education_level') }}" :selected-label="collect($educationLevels)->firstWhere('id', $formData['degree'] ?? null)['name'] ?? ''" />
        @error('formData.degree') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.academic_info.approved_major') *</label>
        <x-educator::forms.select wire:model="formData.academic_major_id" :options="$approvedMajors" option-value="id" option-label="name" placeholder="{{ __('educator::builder.academic_info.select_approved_major') }}" :selected-label="($formData['academic_major_id'] ?? null) === 'custom' ? __('educator::builder.academic_info.is_custom_major') : $approvedMajors->find($formData['academic_major_id'] ?? null)?->name">
            <div wire:click="$set('formData.academic_major_id', 'custom')" @click="open = false" class="dropdown-item">@lang('educator::builder.academic_info.is_custom_major')</div>
        </x-educator::forms.select>
        @error('formData.academic_major_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    @if(isset($formData['is_custom']) && $formData['is_custom'])
        <div class="space-y-1 md:col-span-2">
            <label class="form-label">@lang('educator::builder.academic_info.custom_major_name') *</label>
            <input wire:model.defer="formData.custom_name" type="text" class="form-input" placeholder="@lang('educator::builder.academic_info.custom_major_name_placeholder')">
            @error('formData.custom_name') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    @endif
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.academic_info.granting_university') *</label>
        <input wire:model.defer="formData.graduation_institution" type="text" class="form-input" placeholder="@lang('educator::builder.academic_info.granting_university_placeholder')">
        @error('formData.graduation_institution') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.academic_info.graduation_year') *</label>
        <input wire:model.defer="formData.graduation_year" type="number" class="form-input" placeholder="@lang('educator::builder.academic_info.graduation_year_placeholder')">
        @error('formData.graduation_year') <span class="form-error">{{ $message }}</span> @enderror
    </div>
    <div class="md:col-span-2 space-y-1">
        <label class="form-label">@lang('educator::builder.academic_info.certificate_image')</label>
        @include('educator::livewire.profile.builder.partials.image-uploader', ['model' => 'upload', 'existing' => $existingFile, 'collection' => 'academic_certificate'])
    </div>
    <div class="md:col-span-2 flex justify-end gap-3 mt-4 pt-4 border-t">
        <button wire:click="resetForm" type="button" class="btn-secondary">@lang('educator::builder.cancel')</button>
        <button wire:click="saveQualification" wire:loading.attr="disabled" wire:target="saveQualification" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="saveQualification" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.save_changes')</span>
        </button>
    </div>
</div>
