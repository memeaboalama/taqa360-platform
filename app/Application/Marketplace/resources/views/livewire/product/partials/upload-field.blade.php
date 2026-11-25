<div class="editor-upload @error($uploadModel) editor-upload-invalid @enderror">
    <input id="{{ $uploadId }}" type="file" accept="{{ $uploadAccept }}" wire:model="{{ $uploadModel }}"
        class="visually-hidden editor-upload-input" @if($uploadMultiple ?? false) multiple @endif
        aria-label="{{ $uploadLabel }}" aria-describedby="{{ $uploadId }}-hint">
    <label for="{{ $uploadId }}" class="editor-upload-label">
        <span class="editor-upload-icon" aria-hidden="true"><i class="{{ $uploadIcon ?? 'ri-upload-cloud-2-line' }}"></i></span>
        <span class="editor-upload-content">
            <strong>{{ $uploadLabel }}</strong>
            <span id="{{ $uploadId }}-hint" class="editor-muted">{{ $uploadHint }}</span>
            @if(!empty($uploadSelected))<span class="editor-upload-filename"><i class="ri-check-line me-1" aria-hidden="true"></i>{{ $uploadSelected }}</span>@endif
        </span>
        <span class="editor-upload-button">{{ __(!empty($uploadSelected) ? 'marketplace::products.change_files' : 'marketplace::products.choose_files') }}</span>
    </label>
    <span class="editor-upload-loading text-primary" wire:loading wire:target="{{ $uploadModel }}" role="status">
        <span class="spinner-border spinner-border-sm me-1" aria-hidden="true"></span>@lang('marketplace::products.uploading')
    </span>
</div>
@error($uploadModel)<div class="text-danger mt-1">{{ $message }}</div>@enderror
