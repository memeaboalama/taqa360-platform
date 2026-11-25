<div class="editor-preview" role="dialog" aria-modal="true" aria-labelledby="editor-preview-title" wire:keydown.escape="$set('showPreview', false)" tabindex="-1">
    <div class="editor-preview-content">
        <div class="editor-card-heading">
            <h4 id="editor-preview-title">@lang('marketplace::products.preview')</h4>
            <button type="button" class="btn btn-sm btn-light btn-icon" wire:click="$set('showPreview', false)" aria-label="{{ __('admin::main.actions.close') }}" autofocus><i class="ri-close-line"></i></button>
        </div>
        <div class="editor-card-body">
            <p class="editor-muted mb-4">@lang('marketplace::products.preview_hint')</p>
            <div class="editor-product">
                @if($image || $image_path)<img class="editor-thumbnail" src="{{ $image ? $image->temporaryUrl() : $image_path }}" alt="{{ $title[app()->getLocale()] ?? '' }}">@endif
                <div><h3>{{ $title[app()->getLocale()] ?? $title['ar'] ?? $title['en'] ?? '' }}</h3><strong class="text-primary fs-18">{{ number_format((float) $default_price, 2) }}</strong></div>
            </div>
            <p style="white-space:pre-line">{{ $description[app()->getLocale()] ?? $description['ar'] ?? $description['en'] ?? '' }}</p>
            @php
                $previewFields = $this->visibleFields->filter(fn ($property) => $this->hasFieldValue($inputs[$property->id] ?? null) || isset($propertyUploads[$property->id]));
            @endphp
            <h5 class="fw-semibold mt-4 mb-3">@lang('marketplace::products.specifications')</h5>
            @if($previewFields->isEmpty())
                <p class="editor-muted">@lang('marketplace::products.no_preview_fields')</p>
            @else
                <div class="table-responsive">
                    <table class="table table-striped mb-0"><tbody>
                        @foreach($previewFields as $property)
                            @php
                                $value = $inputs[$property->id] ?? null;
                                $type = $property->meta['input_type'] ?? 'short_text';
                            @endphp
                            <tr>
                                <th>{{ $this->fieldName($property) }}</th>
                                <td>
                                    @if(isset($propertyUploads[$property->id]))
                                        {{ $propertyUploads[$property->id]->getClientOriginalName() }}
                                    @elseif($type === 'boolean')
                                        {{ (string) $value === '1' ? __('marketplace::products.yes') : __('marketplace::products.no') }}
                                    @elseif($type === 'number_range')
                                        {{ $value['min'] ?? '' }} – {{ $value['max'] ?? '' }}
                                    @elseif(is_array($value))
                                        {{ implode('، ', $value) }}
                                    @elseif($type === 'attachment' && filter_var($value, FILTER_VALIDATE_URL) && in_array(strtolower(parse_url($value, PHP_URL_SCHEME) ?? ''), ['http', 'https'], true))
                                        <a href="{{ $value }}" target="_blank" rel="noopener">@lang('marketplace::products.download')</a>
                                    @else
                                        {{ $value }}
                                    @endif
                                    @if(!empty($property->meta['unit']))<span class="text-muted ms-1">{{ $property->meta['unit'] }}</span>@endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody></table>
                </div>
            @endif
        </div>
        <div class="editor-card-heading border-top justify-content-end">
            <button type="button" class="btn btn-primary" wire:click="$set('showPreview', false)">{{ __('admin::main.actions.close') }}</button>
        </div>
    </div>
</div>
