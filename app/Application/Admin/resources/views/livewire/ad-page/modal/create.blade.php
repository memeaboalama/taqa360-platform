<div class="modal fade {{ $showOffcanvas ? 'show' : '' }}" id="createPageModal" tabindex="-1"
     style="display: {{ $showOffcanvas ? 'block' : 'none' }}; padding-right: 17px;" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fe fe-plus-circle me-2"></i>{{ __('admin::lang.Add New Page') }}
                </h6>
                <button type="button" class="btn-close" wire:click="close"></button>
            </div>

            <form wire:submit.prevent="save">
                <div class="modal-body p-4">
                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ __('admin::lang.Page Name') }} <span class="text-danger">*</span></label>
                        <div class="d-flex flex-column gap-2">
                            @foreach(config('app.locales', ['ar', 'en']) as $locale)
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text text-uppercase fw-bold" style="width: 40px;">{{ $locale }}</span>
                                    <input type="text" class="form-control" wire:model="name.{{ $locale }}" placeholder="{{ __('admin::lang.Name') }} ({{ $locale }})">
                                </div>
                                @error("name.$locale") <small class="text-danger">{{ $message }}</small> @enderror
                            @endforeach
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ __('admin::lang.Page Key') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" wire:model="key" placeholder="unique_key">
                        @error('key') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-semibold small">{{ __('admin::lang.Description') }}</label>
                        <div class="d-flex flex-column gap-2">
                            @foreach(config('app.locales', ['ar', 'en']) as $locale)
                                <div class="input-group input-group-sm">
                                    <span class="input-group-text text-uppercase fw-bold" style="width: 40px;">{{ $locale }}</span>
                                    <textarea class="form-control" wire:model="description.{{ $locale }}" rows="1" placeholder="{{ __('admin::lang.Description') }}"></textarea>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" wire:model="status" value="active" {{ $status === 'active' ? 'checked' : '' }} role="switch" id="statusSwitch">
                        <label class="form-check-label small fw-semibold" for="statusSwitch">
                            {{ $status === 'active' ? __('admin::lang.Active') : __('admin::lang.Inactive') }}
                        </label>
                    </div>
                </div>

                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-sm btn-light" wire:click="close">{{ __('admin::lang.Cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <i class="fe fe-save me-1"></i>{{ __('admin::lang.Save') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if($showOffcanvas)
    <div class="modal-backdrop fade show" wire:click="close"></div>
@endif
