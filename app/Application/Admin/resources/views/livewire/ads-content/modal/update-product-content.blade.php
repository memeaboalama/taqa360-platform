<div class="modal fade" id="updateProductContentModal" tabindex="-1" aria-modal="true" role="dialog" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header bg-warning-transparent">
                <h6 class="modal-title fw-bold text-warning">
                    <i class="fe fe-edit me-2"></i>{{ __('admin::lang.Edit Product Content') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body p-4">
                    
                    <div class="mb-3 p-3 bg-light border rounded">
                        <label class="small text-muted mb-1">{{ __('admin::lang.Product') }}</label>
                        <div class="fw-bold">{{ $product_title }}</div>
                    </div>

                    @if(!empty($groups))
                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('admin::lang.Display Group') }}</label>
                            <select class="form-select form-select-sm" wire:model="selected_group_key">
                                @foreach($groups as $group)
                                    <option value="{{ $group['key'] }}">{{ $group['title'] }}</option>
                                @endforeach
                            </select>
                            @error('selected_group_key') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    @endif

                    <div class="row g-2 mb-3">
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('admin::lang.Start Date') }}</label>
                            <input type="date" class="form-control form-control-sm" wire:model="start_date">
                            @error('start_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('admin::lang.End Date') }}</label>
                            <input type="date" class="form-control form-control-sm" wire:model="end_date">
                            @error('end_date') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('admin::lang.Status') }}</label>
                            <select class="form-select form-select-sm" wire:model="status">
                                @foreach(\App\Support\Enum\BannerStatusEnum::cases() as $statusEnum)
                                    <option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</option>
                                @endforeach
                            </select>
                            @error('status') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label small fw-bold">{{ __('admin::lang.Priority') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model="priority" min="0">
                            @error('priority') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal" wire:loading.attr="disabled">{{ __('admin::lang.Cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary" wire:loading.attr="disabled">
                        <span wire:loading.remove wire:target="update">
                            <i class="fe fe-save me-1"></i>{{ __('admin::lang.Save Changes') }}
                        </span>
                        <span wire:loading wire:target="update">
                            <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::lang.Saving') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
