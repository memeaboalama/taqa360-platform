<div x-data="{ isUploading: false, progress: 0 }"
     x-on:livewire-upload-start="isUploading = true"
     x-on:livewire-upload-finish="isUploading = false"
     x-on:livewire-upload-error="isUploading = false"
     x-on:livewire-upload-progress="progress = $event.detail.progress"
     class="relative w-full h-40 bg-gray-50 rounded-lg border-2 border-dashed flex items-center justify-center text-center overflow-hidden group">

    @php
        $temporaryImage = $this->$model;
        $imageExtensions = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'webp', 'svg'];
        $tempIsImage = $temporaryImage && in_array(strtolower($temporaryImage->getClientOriginalExtension()), $imageExtensions);
        $existingIsImage = $existing && str_starts_with((string) $existing->mime_type, 'image/');
    @endphp

    @if ($temporaryImage || $existing)
        <div class="absolute inset-0">
            @if ($tempIsImage)
                <img src="{{ $temporaryImage->temporaryUrl() }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-10"></div>
            @elseif ($existingIsImage)
                <img src="{{ $existing->url }}" class="w-full h-full object-cover">
                <div class="absolute inset-0 bg-black bg-opacity-10"></div>
            @else
                <div class="w-full h-full flex flex-col items-center justify-center gap-2 bg-gray-100 p-4">
                    <svg class="w-10 h-10 text-gray-400 flex-shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z" /></svg>
                    <span class="text-sm text-gray-600 text-center break-all">{{ $temporaryImage?->getClientOriginalName() ?? $existing?->original_name ?? '' }}</span>
                </div>
            @endif
        </div>
    @endif

    <div class="relative z-10 p-4">
        @if (!$temporaryImage && !$existing)
            <label for="{{ $model }}-upload" class="cursor-pointer">
                <svg class="mx-auto h-10 w-10 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5m-13.5-9L12 3m0 0l4.5 4.5M12 3v13.5" /></svg>
                <p class="mt-1 text-sm text-gray-500">@lang('educator::builder.basic_info.upload_prompt')</p>
            </label>
        @endif
    </div>

    @if ($temporaryImage || $existing)
        <div class="absolute top-2 {{ app()->getLocale() == 'ar' ? 'left-2' : 'right-2' }} z-20">
            @if ($temporaryImage)
                <button wire:click="removeTempImage('{{ $model }}')" type="button" class="flex items-center justify-center w-6 h-6 bg-gray-800 bg-opacity-50 text-white rounded-full hover:bg-opacity-75 transition-all" title="@lang('educator::builder.cancel')">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                </button>
            @elseif ($existing)
                <button wire:click="confirmImageRemoval('{{ $collection }}')" type="button" class="flex items-center justify-center w-6 h-6 bg-red-600 text-white rounded-full hover:bg-red-700 transition-all" title="@lang('educator::builder.delete')">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"><path d="M6.28 5.22a.75.75 0 00-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 101.06 1.06L10 11.06l3.72 3.72a.75.75 0 101.06-1.06L11.06 10l3.72-3.72a.75.75 0 00-1.06-1.06L10 8.94 6.28 5.22z" /></svg>
                </button>
            @endif
        </div>
    @endif

    <input wire:model="{{ $model }}" type="file" id="{{ $model }}-upload" class="sr-only">
    <div x-show="isUploading" class="upload-progress-wrapper"><div class="upload-progress-bar" :style="`width: ${progress}%`"></div></div>

    @error($model) <span class="form-error absolute -bottom-5 left-0">{{ $message }}</span> @enderror
</div>
