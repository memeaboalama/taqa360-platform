<div class="space-y-4">
    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.services.service_type') *</label>
        <x-educator::forms.select wire:model="formData.service_id" :options="$approvedServices" option-value="id"
            option-label="name" placeholder="{{ __('educator::builder.services.select_service_type') }}"
            :selected-label="($formData['service_id'] ?? null) === 'custom' ? __('educator::builder.services.is_custom_service') : $approvedServices->find($formData['service_id'] ?? null)?->name">

            <div wire:click="$set('formData.service_id', 'custom')" @click="open = false" class="dropdown-item">
                @lang('educator::builder.services.is_custom_service')
            </div>
        </x-educator::forms.select>
        @error('formData.service_id') <span class="form-error">{{ $message }}</span> @enderror
    </div>

    @if(isset($formData['is_custom']) && $formData['is_custom'])
        <div class="space-y-1">
            <label class="form-label">@lang('educator::builder.services.custom_service_details') *</label>
            <input wire:model.defer="formData.custom_name" type="text" class="form-input"
                placeholder="@lang('educator::builder.services.custom_service_placeholder')">
            @error('formData.custom_name') <span class="form-error">{{ $message }}</span> @enderror
        </div>
    @endif

    <div class="space-y-1">
        <label class="form-label">@lang('educator::builder.services.service_description') *</label>
        <textarea wire:model.defer="formData.custom_description" rows="4" class="form-input"
            placeholder="@lang('educator::builder.services.service_description_placeholder')"></textarea>
        @error('formData.custom_description') <span class="form-error">{{ $message }}</span> @enderror
    </div>
</div>
