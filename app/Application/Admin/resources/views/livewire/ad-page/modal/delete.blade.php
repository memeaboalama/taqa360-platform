<div class="modal fade {{ $showModal ? 'show' : '' }}" id="deletePageModal" tabindex="-1" 
     style="display: {{ $showModal ? 'block' : 'none' }}; background: rgba(0,0,0,0.5);" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body p-5 text-center">
                <div class="mb-4">
                    <div class="avatar avatar-xl bg-danger-transparent rounded-circle mb-3 mx-auto">
                        <i class="fe fe-trash-2 text-danger fs-30"></i>
                    </div>
                    <h4 class="fw-bold mb-2">{{ __('admin::lang.Delete Page') }}</h4>
                    <p class="text-muted">{{ __('admin::lang.Are you sure you want to delete this page?') }}</p>
                    @if($name)
                        <div class="alert alert-light border fw-bold text-dark">
                            {{ is_array($name) ? ($name[app()->getLocale()] ?? $name['en'] ?? '') : $name }}
                        </div>
                    @endif
                    <p class="text-danger small mb-0">
                        <i class="fe fe-alert-triangle me-1"></i>
                        {{ __('admin::lang.This action cannot be undone and will delete all associated placements.') }}
                    </p>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-danger btn-lg" wire:click="delete">
                        {{ __('admin::lang.Yes, Delete') }}
                    </button>
                    <button type="button" class="btn btn-light btn-lg" wire:click="close">
                        {{ __('admin::lang.Cancel') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>
