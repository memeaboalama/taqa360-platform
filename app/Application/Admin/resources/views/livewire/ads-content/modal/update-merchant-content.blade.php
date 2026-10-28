<div class="modal fade" id="updateMerchantContentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            <div class="modal-header bg-warning-transparent">
                <h6 class="modal-title fw-bold text-warning">
                    <i class="fe fe-edit me-2"></i>{{ __('admin::lang.Edit') }} {{ __('admin::lang.Content') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body p-3">
                    
                    <div class="d-flex align-items-center mb-4 p-3 border rounded bg-light">
                        @if($merchant_logo)
                            <img src="{{ \Illuminate\Support\Facades\Storage::url($merchant_logo) }}" alt="{{ $merchant_name }}" class="rounded me-3" style="width: 50px; height: 50px; object-fit: cover;">
                        @else
                            <div class="bg-white rounded d-flex align-items-center justify-content-center me-3 border" style="width: 50px; height: 50px;">
                                <i class="fe fe-store text-muted fs-20"></i>
                            </div>
                        @endif
                        <div>
                            <h6 class="mb-1 fw-bold">{{ $merchant_name }}</h6>
                            <span class="badge bg-primary-transparent text-primary">Merchant Content</span>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Start Date') }}</label>
                            <input type="date" class="form-control form-control-sm" wire:model.defer="start_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.End Date') }}</label>
                            <input type="date" class="form-control form-control-sm" wire:model.defer="end_date">
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Status') }}</label>
                            <select class="form-select form-select-sm" wire:model.defer="status">
                                @foreach(\App\Support\Enum\ProductContentStatusEnum::cases() as $status)
                                    <option value="{{ $status->value }}">{{ $status->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-6">
                            <label class="form-label small mb-1">{{ __('admin::lang.Priority') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model.defer="priority" min="0" max="100" placeholder="0">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('admin::lang.Close') }}</button>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <span wire:loading.remove wire:target="update">
                            <i class="fe fe-save me-1"></i>{{ __('admin::lang.Update') }}
                        </span>
                        <span wire:loading wire:target="update">
                            <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::lang.Wait') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
