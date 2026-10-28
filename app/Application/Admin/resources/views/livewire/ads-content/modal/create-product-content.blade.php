<div class="modal fade" id="createProductContentModal" tabindex="-1" aria-modal="true" role="dialog" wire:ignore.self data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" style="max-width: 600px;">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fe fe-plus-circle me-2"></i>{{ __('admin::lang.Add Product') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close" wire:loading.attr="disabled"></button>
            </div>

            <form wire:submit.prevent="store">
                <div class="modal-body p-4">
                    
                    @if(!empty($groups))
                        <div class="mb-3">
                            <label class="form-label small fw-bold">{{ __('admin::lang.Display Group') }} <span class="text-danger">*</span></label>
                            <select class="form-select form-select-sm" wire:model="selected_group_key">
                                @foreach($groups as $group)
                                    <option value="{{ $group['key'] }}">{{ $group['title'] }}</option>
                                @endforeach
                            </select>
                            @error('selected_group_key') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label small fw-bold">{{ __('admin::lang.Select Products') }} <span class="text-danger">*</span></label>
                        <livewire:admin::ads-content.shared.live-search
                            :type="'product'"
                            :emitEvent="'itemSelected'"
                            :key="'search-create-product-content'"
                        />
                        @error('selected_products') <small class="text-danger d-block mt-1">{{ $message }}</small> @enderror

                        @if(!empty($selected_products))
                            <div class="mt-2 border rounded p-2 bg-light">
                                <label class="small text-muted mb-2 d-block">{{ count($selected_products) }} {{ __('admin::lang.Products Selected') }}</label>
                                <div class="d-flex flex-wrap gap-2">
                                    @foreach($selected_products as $index => $prod)
                                        <span class="badge bg-white text-dark border d-flex align-items-center p-2">
                                            @if(isset($prod['image']))
                                                <img src="{{ $prod['image'] }}" class="rounded-circle me-2" style="width: 20px; height: 20px; object-fit: cover;">
                                            @endif
                                            {{ $prod['title'] }}
                                            <button type="button" class="btn-close ms-2" style="width: 0.5em; height: 0.5em;" wire:click="removeProduct({{ $index }})"></button>
                                        </span>
                                    @endforeach
                                </div>
                            </div>
                        @endif
                    </div>

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
                                @foreach(\App\Support\Enum\ProductContentStatusEnum::cases() as $statusEnum)
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
                        <span wire:loading.remove wire:target="store">
                            <i class="fe fe-save me-1"></i>{{ __('admin::lang.Save') }}
                        </span>
                        <span wire:loading wire:target="store">
                            <span class="spinner-border spinner-border-sm me-1"></span>{{ __('admin::lang.Saving') }}
                        </span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
