<div class="modal fade" id="createMerchantContentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fe fe-plus-circle me-2"></i>{{ __('admin::home.Add') }} {{ __('admin::lang.Merchant') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="store">
                <div class="modal-body p-3" style="max-height: 70vh; overflow-y: auto;">

                    <div class="mb-3">
                        <label class="form-label small fw-bold text-primary mb-2">{{ __('admin::lang.Search Merchant') }}</label>
                        <livewire:admin::ads-content.shared.live-search
                            type="merchant"
                            emitEvent="merchantSelected"
                            key="search-create-merchant-content" />
                    </div>

                    @if(!empty($selected_merchants))
                    <div class="mb-3">
                        <label class="form-label small mb-2">{{ __('admin::lang.Selected Merchants') }}</label>
                        <div class="list-group">
                            @foreach($selected_merchants as $index => $item)
                            <div class="list-group-item d-flex align-items-center justify-content-between p-2">
                                <div class="d-flex align-items-center">
                                    @if(!empty($item['image']))
                                    <img src="{{ \Illuminate\Support\Facades\Storage::url($item['image']) }}" alt="{{ $item['title'] }}" class="rounded me-2" style="width: 30px; height: 30px; object-fit: cover;">
                                    @else
                                    <div class="bg-light rounded d-flex align-items-center justify-content-center me-2" style="width: 30px; height: 30px;">
                                        <i class="fe fe-image text-muted"></i>
                                    </div>
                                    @endif
                                    <span class="small fw-semibold">{{ $item['title'] }}</span>
                                </div>
                                <button type="button" class="btn btn-sm btn-link text-danger p-0" wire:click="removeMerchant({{ $index }})">
                                    <i class="fe fe-x"></i>
                                </button>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    <h6 class="text-primary mb-2 pb-1 border-bottom small fw-bold mt-4">{{ __('admin::lang.Configuration') }}</h6>

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
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('admin::home.Close') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary">
                        <span wire:loading.remove wire:target="store">
                            <i class="fe fe-save me-1"></i>{{ __('admin::home.Save') }}
                        </span>
                        <span wire:loading wire:target="store">
                            <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::home.Wait') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>