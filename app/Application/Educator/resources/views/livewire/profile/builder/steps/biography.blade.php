<div x-data="{ editingBio: false }">
    <div class="mb-8">
        <div class="flex items-center gap-4 mb-4">
            <div class="w-12 h-12 rounded-full bg-primary/10 flex items-center justify-center">
                <svg class="w-6 h-6 text-primary" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.325 3.498.097 6.484-.673 8.98-2.54 1.135-.845 2.268-1.928 2.268-3.227 0-1.299-.938-2.381-2.128-3.138C15.908 9.59 13.556 8.5 10.5 8.5c-3.056 0-5.408 1.09-7.128 1.862-1.19.757-2.128 1.839-2.128 3.138z" /></svg>
            </div>
            <div>
                <h2 class="text-2xl font-bold text-foreground">@lang('educator::builder.steps.biography')</h2>
                <p class="text-gray-600">@lang('educator::builder.biography.subtitle')</p>
            </div>
        </div>
    </div>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl shadow-lg p-6 text-center sticky top-24">
                <div class="relative w-32 h-32 mx-auto" x-data="{ isUploading: false, progress: 0 }" x-on:livewire-upload-start="isUploading = true" x-on:livewire-upload-finish="isUploading = false" x-on:livewire-upload-progress="progress = $event.detail.progress">
                    <img src="{{ $profileImage ? $profileImage->temporaryUrl() : ($existingProfileImage?->url ?? 'https://ui-avatars.com/api/?name=' . urlencode($educator->name) . '&background=F8C421&color=333333') }}" class="w-32 h-32 rounded-full object-cover">
                    <label for="profileImageUpload" class="absolute -bottom-1 -right-1 bg-white p-2 rounded-full shadow-md cursor-pointer hover:bg-gray-100 transition-transform hover:scale-110">
                        <svg class="w-5 h-5 text-gray-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 015.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 002.25 2.25h15A2.25 2.25 0 0021.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 00-1.134-.175 2.31 2.31 0 01-1.64-1.055l-.822-1.316a2.192 2.192 0 00-1.736-1.039 48.776 48.776 0 00-5.232 0 2.192 2.192 0 00-1.736 1.039l-.821 1.316z" /><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 11-9 0 4.5 4.5 0 019 0zM18.75 10.5h.008v.008h-.008V10.5z" /></svg>
                        <input type="file" wire:model="profileImage" id="profileImageUpload" class="hidden">
                    </label>
                    <div x-show="isUploading" x-cloak class="absolute inset-0 flex items-center justify-center bg-white/70 rounded-full">
                        <svg class="w-10 h-10 text-primary animate-spin" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                    </div>
                </div>
                @error('profileImage') <span class="form-error text-center block mt-2">{{ $message }}</span> @enderror
                <h3 class="font-bold text-xl text-foreground mt-4">{{ $educator->name }}</h3>
                <p class="text-sm text-gray-500">{{ $educator->profile->specialization ?? 'Educator' }}</p>
                <div class="flex justify-center items-center gap-3 mt-4">
                    @foreach($socialLinks as $link)
                        @if(!empty($link['url']))
                            <a href="{{ $link['url'] }}" target="_blank" class="text-gray-400 hover:text-primary transition-colors">
                                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A11.953 11.953 0 0112 16.5c-2.998 0-5.74-1.1-7.843-2.918m15.686-7.5A8.959 8.959 0 0021 12c0 .778-.099 1.533-.284 2.253m0 0a8.997 8.997 0 007.843-4.582M12 10.5a8.997 8.997 0 017.843-4.582" /></svg>
                            </a>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <div class="flex justify-between items-center mb-2">
                    <h3 class="text-lg font-semibold text-foreground">@lang('educator::builder.biography.biography')</h3>
                    <button @click="editingBio = !editingBio; $nextTick(() => { if(editingBio) $refs.bioTextarea.focus() })" class="text-sm font-medium text-primary hover:underline">
                        <span x-show="!editingBio">@lang('educator::builder.edit')</span>
                        <span x-show="editingBio" x-cloak>@lang('educator::builder.cancel')</span>
                    </button>
                </div>
                <div x-show="!editingBio" class="text-gray-600 prose prose-sm max-w-none">
                    {!! nl2br(e($bio['description'] ?? '')) ?: '<span class="text-gray-400">' . __('educator::builder.biography.no_bio_added') . '</span>' !!}
                </div>
                <div x-show="editingBio" x-cloak>
                    <textarea wire:model.lazy="bio.description" x-ref="bioTextarea" rows="8" class="form-input w-full" placeholder="@lang('educator::builder.biography.biography_placeholder')"></textarea>
                    <div class="text-right rtl:text-left text-sm text-gray-500 mt-1">
                        {{ \Illuminate\Support\Str::length($bio['description'] ?? '') }} / 2000
                    </div>
                </div>
                @error('bio.description') <span class="form-error">{{ $message }}</span> @enderror
            </div>
            <div class="bg-white rounded-lg border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-foreground mb-4">@lang('educator::builder.biography.social_links')</h3>
                <div class="space-y-3">
                    @foreach($socialLinks as $index => $link)
                        <div class="flex items-center gap-3" wire:key="social-link-{{ $index }}">
                            <div class="w-1/3">
                                <select wire:model.lazy="socialLinks.{{$index}}.platform" class="form-input-sm w-full">
                                    <option value="">@lang('educator::builder.biography.select_platform')</option>
                                    @foreach($platformOptions as $value => $label)
                                        <option value="{{ $value }}">{{ $label }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="flex-grow">
                                <input wire:model.lazy="socialLinks.{{$index}}.url" type="url" placeholder="@lang('educator::builder.biography.profile_url_placeholder')" class="form-input-sm w-full">
                            </div>
                            <button wire:click="removeSocialLink({{ $index }})" type="button" class="text-red-500 p-1.5 hover:bg-red-50 rounded-full">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M14.74 9l-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 01-2.244 2.077H8.084a2.25 2.25 0 01-2.244-2.077L4.772 5.79m14.456 0a4.81 4.81 0 00-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 4.81 0 013.478-.397m7.5 0v-.916c0-1.18-.91-2.134-2.09-2.201a51.964 51.964 0 00-3.32 0c-1.18.067-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 00-7.5 0" /></svg>
                            </button>
                        </div>
                    @endforeach
                    @if(count($socialLinks) < 5)
                        <button wire:click="addSocialLink" type="button" class="w-full border-2 border-dashed border-gray-300 hover:border-primary text-gray-500 hover:text-primary py-2 px-4 rounded-lg flex items-center justify-center gap-2">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" /></svg>
                            @lang('educator::builder.biography.add_new_link')
                        </button>
                    @endif
                </div>
            </div>
        </div>
    </div>
    <div class="flex justify-between pt-6 border-t mt-8">
        <button wire:click="$emitUp('goToPreviousStep')" type="button" class="btn-secondary">@lang('educator::builder.previous')</button>
        <button wire:click="saveAndNext" wire:loading.attr="disabled" wire:target="saveAndNext" type="button" class="btn-primary inline-flex items-center justify-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed">
            <svg wire:loading wire:target="saveAndNext" x-cloak class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span>@lang('educator::builder.next')</span>
        </button>
    </div>
</div>
