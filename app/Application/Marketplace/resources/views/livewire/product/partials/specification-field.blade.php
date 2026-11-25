@php
    $meta = $property->meta ?? [];
    $inputType = $meta['input_type'] ?? 'short_text';
    $fieldId = 'editor-specification-' . $property->id;
    $fieldKey = 'inputs.' . $property->id;
    $columnSpan = max(2, min(12, (int) ($meta['column_span'] ?? ($inputType === 'long_text' ? 12 : 6))));
@endphp
<div class="col-12 col-md-{{ $columnSpan }}" wire:key="editor-field-{{ $property->id }}">
    <label id="{{ $fieldId }}-label" @unless(in_array($inputType, ['boolean', 'multiselect'], true)) for="{{ $fieldId }}" @endunless class="form-label">
        {{ $this->fieldName($property) }}
        @if(!empty($meta['unit']))<span class="text-muted">({{ $meta['unit'] }})</span>@endif
        @if(!empty($meta['is_required']))<span class="text-danger">*</span>@endif
    </label>
    @switch($inputType)
        @case('long_text')
            <textarea id="{{ $fieldId }}" wire:model.lazy="{{ $fieldKey }}" rows="3" class="form-control @error($fieldKey) is-invalid @enderror"></textarea>
            @break
        @case('select')
            <select id="{{ $fieldId }}" wire:model="{{ $fieldKey }}" class="form-select @error($fieldKey) is-invalid @enderror">
                <option value="">{{ __('admin::main.actions.select') }}</option>
                @foreach($meta['options'] ?? [] as $option)<option value="{{ $option }}">{{ $option }}</option>@endforeach
            </select>
            @break
        @case('multiselect')
            @php
                $selectedValues = is_array($inputs[$property->id] ?? null)
                    ? array_map('strval', $inputs[$property->id])
                    : [];
                $selectedLabels = collect($meta['options'] ?? [])
                    ->filter(fn ($option) => in_array((string) $option, $selectedValues, true))
                    ->implode(', ');
            @endphp
            <details class="editor-multiselect" wire:ignore.self wire:key="editor-multiselect-{{ $property->id }}">
                <summary class="form-select {{ $errors->has($fieldKey) ? 'is-invalid' : '' }}" aria-labelledby="{{ $fieldId }}-label">
                    {{ $selectedLabels !== '' ? $selectedLabels : __('admin::main.actions.select') }}
                </summary>
                <div class="editor-multiselect-options">
                    @foreach($meta['options'] ?? [] as $optionIndex => $option)
                        <label class="form-check editor-multiselect-option" wire:key="editor-multiselect-option-{{ $property->id }}-{{ $optionIndex }}">
                            <input type="checkbox" class="form-check-input" value="{{ $option }}" wire:model="{{ $fieldKey }}">
                            <span class="form-check-label">{{ $option }}</span>
                        </label>
                    @endforeach
                </div>
            </details>
            @break
        @case('boolean')
            <div class="editor-boolean-options d-flex gap-2" role="radiogroup" aria-labelledby="{{ $fieldId }}-label">
                <input type="radio" class="btn-check" name="specification-{{ $property->id }}" id="{{ $fieldId }}-yes"
                       value="1" wire:model="{{ $fieldKey }}" autocomplete="off">
                <label class="btn btn-outline-primary flex-fill @error($fieldKey) is-invalid @enderror" for="{{ $fieldId }}-yes">
                    @lang('marketplace::products.yes')
                </label>
                <input type="radio" class="btn-check" name="specification-{{ $property->id }}" id="{{ $fieldId }}-no"
                       value="0" wire:model="{{ $fieldKey }}" autocomplete="off">
                <label class="btn btn-outline-primary flex-fill @error($fieldKey) is-invalid @enderror" for="{{ $fieldId }}-no">
                    @lang('marketplace::products.no')
                </label>
            </div>
            @break
        @case('number_range')
            <div class="d-flex gap-2">
                <input id="{{ $fieldId }}" type="number" step="any" wire:model.lazy="{{ $fieldKey }}.min" class="form-control @error($fieldKey . '.min') is-invalid @enderror" placeholder="{{ __('marketplace::products.min') }}" aria-label="{{ $this->fieldName($property) }} {{ __('marketplace::products.min') }}">
                <input type="number" step="any" wire:model.lazy="{{ $fieldKey }}.max" class="form-control @error($fieldKey . '.max') is-invalid @enderror" placeholder="{{ __('marketplace::products.max') }}" aria-label="{{ $this->fieldName($property) }} {{ __('marketplace::products.max') }}">
            </div>
            @error($fieldKey . '.min')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            @error($fieldKey . '.max')<div class="text-danger mt-1">{{ $message }}</div>@enderror
            @break
        @case('attachment')
            @if(!empty($inputs[$property->id]) && filter_var($inputs[$property->id], FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($inputs[$property->id], PHP_URL_SCHEME) ?? ''), ['http', 'https'], true))
                <div class="d-flex align-items-center gap-2 mb-2">
                    <a href="{{ $inputs[$property->id] }}" target="_blank" rel="noopener">@lang('marketplace::products.download')</a>
                    <button type="button" class="btn btn-sm btn-outline-danger" wire:click="$set('{{ $fieldKey }}', null)" data-editor-mutation>@lang('marketplace::products.remove')</button>
                </div>
            @endif
            @include('marketplace::livewire.product.partials.upload-field', [
                'uploadId' => $fieldId, 'uploadModel' => 'propertyUploads.' . $property->id,
                'uploadAccept' => 'application/pdf,image/jpeg,image/png',
                'uploadLabel' => $this->fieldName($property),
                'uploadHint' => __('marketplace::products.attachment_hint'),
                'uploadSelected' => isset($propertyUploads[$property->id]) ? $propertyUploads[$property->id]->getClientOriginalName() : '',
            ])
            @break
        @default
            <input id="{{ $fieldId }}" type="{{ match($inputType) { 'integer', 'decimal' => 'number', 'date' => 'date', default => 'text' } }}" @if($inputType === 'integer') step="1" @elseif($inputType === 'decimal') step="any" @endif wire:model.lazy="{{ $fieldKey }}" class="form-control @error($fieldKey) is-invalid @enderror">
    @endswitch
    @error($fieldKey)<div class="text-danger mt-1">{{ $message }}</div>@enderror
    @error($fieldKey . '.*')<div class="text-danger mt-1">{{ $message }}</div>@enderror
    @if(!empty($meta['merchant_guidance']))<p class="editor-muted mt-2 mb-0">{{ $meta['merchant_guidance'] }}</p>@endif
</div>
