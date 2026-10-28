<div class="modal fade" id="reactivateReplacementModal" tabindex="-1" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" style="max-width: 550px;">
        <div class="modal-content">
            <div class="modal-header {{ $isLimitReached ? 'bg-warning-transparent' : 'bg-primary-transparent' }}">
                <h6 class="modal-title fw-bold {{ $isLimitReached ? 'text-warning' : 'text-primary' }}">
                    @if($isLimitReached)
                        <i class="fe fe-alert-triangle me-2"></i>{{ __('admin::lang.Max Limit Reached') }}
                    @else
                        <i class="fe fe-rotate-ccw me-2"></i>{{ __('admin::lang.Reactivate Content') }}
                    @endif
                </h6>
                <button type="button" class="btn-close" wire:click="closeModal"></button>
            </div>

            <div class="modal-body p-3">
                @if($isLimitReached)
                    <div class="alert alert-warning mb-3">
                        <div class="d-flex align-items-center">
                            <i class="fe fe-info fs-4 me-2"></i>
                            <div>
                                <strong>{{ __('admin::lang.Maximum Active Items Reached') }}</strong><br>
                                <small>{{ __('admin::lang.Current') }}: {{ $currentCount }} / {{ $maxItems }}</small>
                            </div>
                        </div>
                    </div>
                @endif

                <div class="row g-2 mb-3">
                    <div class="col-12">
                        <label class="form-label small fw-bold">{{ __('admin::lang.Schedule (Optional)') }}</label>
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1">{{ __('admin::lang.Start Date') }}</label>
                        <input type="datetime-local" class="form-control form-control-sm" wire:model.defer="start_date">
                    </div>
                    <div class="col-6">
                        <label class="form-label small mb-1">{{ __('admin::lang.End Date') }}</label>
                        <input type="datetime-local" class="form-control form-control-sm" wire:model.defer="end_date">
                    </div>
                </div>

                @if($isLimitReached)
                    <p class="mb-2 small fw-bold text-muted">
                        {{ __('admin::lang.Please select an active item to deactivate and replace') }}:
                    </p>

                    <div class="list-group">
                        @forelse($activeBanners as $banner)
                            <label class="list-group-item list-group-item-action" style="cursor: pointer;">
                                <div class="d-flex align-items-center">
                                    <input type="radio"
                                           class="form-check-input me-3"
                                           wire:model="selectedBannerToReplace"
                                           value="{{ $banner->id }}">
                                    <div class="flex-fill">
                                        <div class="fw-semibold">{{ $banner->name }}</div>
                                        <small class="text-muted">
                                            {{ __('admin::lang.Priority') }}: {{ $banner->priority }} |
                                            {{ __('admin::lang.Updated') }}: {{ $banner->updated_at->diffForHumans() }}
                                        </small>
                                    </div>
                                </div>
                            </label>
                        @empty
                            <p class="text-muted text-center">{{ __('admin::lang.No active items') }}</p>
                        @endforelse
                    </div>
                @endif
            </div>

            <div class="modal-footer bg-light py-2">
                <button type="button" class="btn btn-sm btn-light" wire:click="closeModal">
                    {{ __('admin::lang.Cancel') }}
                </button>

                @if($isLimitReached)
                    <button type="button"
                            class="btn btn-sm btn-warning"
                            wire:click="reactivateWithReplacement"
                            @if(!$selectedBannerToReplace) disabled @endif>
                        <i class="fe fe-refresh-cw me-1"></i>{{ __('admin::lang.Replace & Reactivate') }}
                    </button>
                @else
                    <button type="button"
                            class="btn btn-sm btn-primary"
                            wire:click="reactivateBanner">
                        <i class="fe fe-check me-1"></i>{{ __('admin::lang.Reactivate') }}
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>
</div>

@push('js')
<script>
    document.addEventListener('livewire:load', function () {
        window.addEventListener('show-reactivate-modal', event => {
            var myModalEl = document.getElementById('reactivateReplacementModal');
            var myModal = new bootstrap.Modal(myModalEl);
            myModal.show();
        });

        window.addEventListener('hide-reactivate-modal', event => {
            var myModalEl = document.getElementById('reactivateReplacementModal');
            var modal = bootstrap.Modal.getInstance(myModalEl);
            if (modal) {
                modal.hide();
            }
        });
    });
</script>
@endpush
