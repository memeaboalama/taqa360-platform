@php
    $previewLabel = app()->getLocale() === 'ar' ? ($property['name_ar'] ?? '') : ($property['name_en'] ?? '');
@endphp
@if(($property['input_type'] ?? '') === 'multiselect')
    <details class="editor-multiselect" data-specification-choice-preview wire:ignore.self>
        <summary class="form-select" aria-label="{{ $previewLabel }}" data-preview-choice-label data-placeholder="{{ __('admin::main.actions.select') }}">
            {{ __('admin::main.actions.select') }}
        </summary>
        <div class="editor-multiselect-options">
            @foreach($property['options'] ?? [] as $option)
                <label class="form-check editor-multiselect-option">
                    <input type="checkbox" class="form-check-input" value="{{ $option }}">
                    <span class="form-check-label">{{ $option }}</span>
                </label>
            @endforeach
        </div>
    </details>
@else
    <select class="form-select" aria-label="{{ $previewLabel }}">
        <option value="">{{ __('admin::main.actions.select') }}</option>
        @foreach($property['options'] ?? [] as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach
    </select>
@endif
