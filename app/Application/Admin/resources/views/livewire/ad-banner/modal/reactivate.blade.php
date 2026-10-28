<div class="modal fade" id="reactivateModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
        <div class="modal-content">
            @if($showReplacementModal)
                <div class="modal-header bg-warning-transparent">
                    <h6 class="modal-title fw-bold text-warning">
                        <i class="fe fe-alert-triangle me-2"></i>{{ __('admin::lang.Max Limit Reached') }}
                    </h6>
                    <button type="button" class="btn-close" wire:click="$set('showReplacementModal', false)"></button>
                </div>

                <div class="modal-body p-3">
                    <div class="alert alert-warning mb-3">
                        <strong>{{ __('admin::lang.Limit Exceeded') }}</strong><br>
                        {{ __('admin::lang.The maximum number of active banners') }}
                        (<strong>{{ $maxItems }}</strong>) {{ __('admin::lang.has been reached') }}.
                    </div>

                    <p class="text-muted small mb-3">
                        {{ __('admin::lang.Please select a banner to deactivate and replace with') }}:
                        <strong>{{ $bannerName }}</strong>
                    </p>

                    <div class="list-group">
                        @forelse($activeBanners as $banner)
                            <label class="list-group-item list-group-item-action d-flex gap-3" style="cursor: pointer;">
                                <input type="radio"
                                       wire:model="selectedBannerToReplace"
                                       value="{{ $banner->id }}"
                                       name="banner-replace">
                                <div class="flex-fill">
                                    <div class="fw-semibold">{{ $banner->name }}</div>
                                    <small class="text-muted">
                                        {{ __('admin::lang.Priority') }}: {{ $banner->priority }} |
                                        {{ __('admin::lang.Created') }}: {{ $banner->created_at->diffForHumans() }}
                                    </small>
                                </div>
                            </label>
                        @empty
                            <p class="text-center text-muted">{{ __('admin::lang.No active banners found') }}</p>
                        @endforelse
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-light" wire:click="$set('showReplacementModal', false)">
                        {{ __('admin::lang.Cancel') }}
                    </button>
                    <button type="button"
                            class="btn btn-sm btn-warning"
                            wire:click="reactivateWithReplacement"
                            @if(!$selectedBannerToReplace) disabled @endif>
                        <i class="fe fe-refresh-cw me-1"></i>{{ __('admin::lang.Replace & Reactivate') }}
                    </button>
                </div>
            @endif
        </div>
    </div>
</div>
