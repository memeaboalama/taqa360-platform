<div>
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                 <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.501 20.118a7.5 7.5 0 0114.998 0A17.933 17.933 0 0112 21.75c-2.676 0-5.216-.584-7.499-1.632z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.basic_info')</h2>
                <p class="text-gray-600">@lang('educator::builder.basic_info.subtitle')</p>
            </div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 lg:gap-x-12 gap-y-8">
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-foreground border-b pb-2">@lang('educator::builder.basic_info.personal_and_professional_info')</h3>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                <div class="space-y-1 sm:col-span-2">
                    <label for="name" class="form-label">@lang('educator::builder.basic_info.name') *</label>
                    <input wire:model.defer="name" id="name" type="text" required class="form-input @error('name') border-red-500 @enderror">
                    @error('name') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1">
                    <label class="form-label">@lang('educator::builder.basic_info.nationality') *</label>
                    <x-educator::forms.select wire:model="nationality_id" :options="$nationalities" option-value="id" option-label="title" placeholder="{{ __('educator::builder.basic_info.select_nationality') }}" :selected-label="$nationalities->firstWhere('id', $nationality_id)?->title" />
                    @error('nationality_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1">
                    <label class="form-label">@lang('educator::builder.basic_info.gender') *</label>
                    <div class="flex gap-6 pt-3">
                        <label class="flex items-center cursor-pointer"><input wire:model="gender" type="radio" value="male" class="radio-input"><span class="radio-label">@lang('educator::builder.basic_info.male')</span></label>
                        <label class="flex items-center cursor-pointer"><input wire:model="gender" type="radio" value="female" class="radio-input"><span class="radio-label">@lang('educator::builder.basic_info.female')</span></label>
                    </div>
                    @error('gender') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1">
                    <label class="form-label">@lang('educator::builder.basic_info.country') *</label>
                    <x-educator::forms.select wire:model="country_id" :options="$countries" option-value="id" option-label="title" placeholder="{{ __('educator::builder.basic_info.country_placeholder') }}" :selected-label="$countries->firstWhere('id', $country_id)?->title" />
                    @error('country_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1">
                    <label class="form-label">@lang('educator::builder.basic_info.city') *</label>
                    <x-educator::forms.select wire:model="city_id" :options="$cities" option-value="id" option-label="title" placeholder="{{ __('educator::builder.basic_info.city_placeholder') }}" :disabled="empty($cities)" :selected-label="collect($cities)->firstWhere('id', $city_id)?->title" />
                    @error('city_id') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1 sm:col-span-2">
                    <label for="experienceYears" class="form-label">@lang('educator::builder.basic_info.experience_years') *</label>
                    <input wire:model.defer="experience_years" id="experienceYears" type="number" class="form-input" placeholder="@lang('educator::builder.basic_info.experience_years_placeholder')">
                    @error('experience_years') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1 sm:col-span-2">
                    <label for="specialization" class="form-label">@lang('educator::builder.basic_info.specialization')</label>
                    <input wire:model.defer="specialization" id="specialization" type="text" class="form-input @error('specialization') border-red-500 @enderror" placeholder="@lang('educator::builder.basic_info.specialization_placeholder')">
                    @error('specialization') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>
        <div class="space-y-6">
            <h3 class="text-lg font-semibold text-foreground border-b pb-2">@lang('educator::builder.basic_info.account_and_identity')</h3>
            <div class="space-y-5">
                <div class="space-y-1">
                    <label for="email" class="form-label">@lang('educator::builder.basic_info.email') *</label>
                    <input wire:model.defer="email" id="email" type="email" required class="form-input @error('email') border-red-500 @enderror">
                    @error('email') <span class="form-error">{{ $message }}</span> @enderror
                </div>
                <div class="space-y-1">
                    <label for="phone" class="form-label">@lang('educator::builder.basic_info.phone') *</label>
                    <input wire:model.defer="phone" id="phone" type="tel" required class="form-input @error('phone') border-red-500 @enderror">
                    @error('phone') <span class="form-error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="space-y-5 border-t pt-6">
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                    <div class="space-y-1">
                        <label class="form-label">@lang('educator::builder.basic_info.identity_type') *</label>
                        @php
                            $identityTypes = [['id' => 'national_id', 'name' => __('educator::builder.basic_info.national_id')], ['id' => 'passport', 'name' => __('educator::builder.basic_info.passport')]];
                        @endphp
                        <x-educator::forms.select wire:model="identity_type" :options="$identityTypes" :is-array="true" option-value="id" option-label="name" placeholder="{{ __('educator::builder.basic_info.select_identity_type') }}" :selected-label="collect($identityTypes)->firstWhere('id', $identity_type)['name'] ?? ''" />
                        @error('identity_type') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                    <div class="space-y-1">
                        <label class="form-label">@lang('educator::builder.basic_info.identity_id') *</label>
                        <input wire:model.defer="identity_id" type="text" required class="form-input @error('identity_id') border-red-500 @enderror">
                        @error('identity_id') <span class="form-error">{{ $message }}</span> @enderror
                    </div>
                </div>
                @if($identity_type === 'national_id')
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-x-6 gap-y-5">
                        <div class="space-y-1">
                            <label class="form-label" for="front-image-upload">@lang('educator::builder.basic_info.identity_image_front')</label>
                            @include('educator::livewire.profile.builder.partials.image-uploader', [
                                'model' => 'identityImageFront',
                                'existing' => $existingIdentityImageFront,
                                'collection' => 'identity_front'
                            ])
                        </div>
                        <div class="space-y-1">
                            <label class="form-label" for="back-image-upload">@lang('educator::builder.basic_info.identity_image_back')</label>
                             @include('educator::livewire.profile.builder.partials.image-uploader', [
                                'model' => 'identityImageBack',
                                'existing' => $existingIdentityImageBack,
                                'collection' => 'identity_back'
                            ])
                        </div>
                    </div>
                @elseif($identity_type === 'passport')
                     <div class="space-y-1">
                        <label class="form-label" for="passport-image-upload">@lang('educator::builder.basic_info.identity_image_front')</label>
                        @include('educator::livewire.profile.builder.partials.image-uploader', [
                            'model' => 'identityImageFront',
                            'existing' => $existingPassportImage,
                            'collection' => 'passport_image'
                        ])
                    </div>
                @endif
            </div>
        </div>
    </div>
    <div class="flex justify-between pt-6 border-t mt-8">
        <button type="button" disabled class="btn-secondary disabled:opacity-50">@lang('educator::builder.previous')</button>
        <button wire:click="save" wire:loading.attr="disabled" wire:target="save" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="save" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.next')</span>
        </button>
    </div>
</div>
