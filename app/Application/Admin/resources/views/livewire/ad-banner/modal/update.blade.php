<div class="modal fade" id="updateBannerModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable" style="max-width: 750px;">
        <div class="modal-content">
            <div class="modal-header bg-warning-transparent">
                <h6 class="modal-title fw-bold text-warning">
                    <i class="fe fe-edit me-2"></i>{{ __('admin::lang.Edit Banner') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="update">
                <div class="modal-body p-3" style="max-height: 65vh; overflow-y: auto;">
                    <div class="mb-2">
                        <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Internal Name') }} <span class="text-danger">*</span></label>
                        <input type="text" class="form-control form-control-sm" wire:model="name" placeholder="For internal reference">
                        @error('name') <small class="text-danger">{{ $message }}</small> @enderror
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Title (EN)') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="title_en">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Title (AR)') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="title_ar">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Subtitle (EN)') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="subtitle_en">
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Subtitle (AR)') }}</label>
                            <input type="text" class="form-control form-control-sm" wire:model="subtitle_ar">
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-6">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Desktop Image') }}</label>
                            @if($image_desktop)
                                <div class="mb-1">
                                    <a href="{{ Storage::url($image_desktop) }}" target="_blank" class="small text-primary">View Current</a>
                                </div>
                            @endif
                            <input type="file" class="form-control form-control-sm" wire:model="new_image_desktop">
                            @error('new_image_desktop') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                        <div class="col-6">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Mobile Image') }}</label>
                            @if($image_mobile)
                                <div class="mb-1">
                                    <a href="{{ Storage::url($image_mobile) }}" target="_blank" class="small text-primary">View Current</a>
                                </div>
                            @endif
                            <input type="file" class="form-control form-control-sm" wire:model="new_image_mobile">
                            @error('new_image_mobile') <small class="text-danger">{{ $message }}</small> @enderror
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Action Type') }}</label>
                            <select class="form-select form-select-sm" wire:model.live="click_action_type">
                                @foreach(\App\Support\Enum\ClickActionTypeEnum::cases() as $type)
                                    <option value="{{ $type->value }}">{{ $type->label() }}</option>
                                @endforeach
                            </select>
                        </div>

                        @if($click_action_type !== 'none' && $click_action_type !== 'url')
                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Search') }} {{ ucfirst($click_action_type) }}</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model.live.debounce.300ms="searchQuery"
                                   placeholder="Type to search...">

                            @if(!empty($searchResults) && count($searchResults) > 0)
                                <div class="search-results-dropdown mt-1 border rounded shadow-sm" style="max-height: 150px; overflow-y: auto;">
                                    @foreach($searchResults as $result)
                                        <div class="search-result-item p-2 border-bottom"
                                             wire:click="selectSearchResult({{ $result->id }})"
                                             style="cursor: pointer;">
                                            <small>{{ $result->title ?? $result->name }}</small>
                                        </div>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        @endif

                        <div class="col-12">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.URL / ID') }}</label>
                            <input type="text"
                                   class="form-control form-control-sm"
                                   wire:model="click_action_url"
                                   placeholder="https://..."
                                   @if($click_action_type !== 'url' && $click_action_type !== 'none') readonly @endif>
                        </div>
                    </div>

                    <div class="row g-2 mb-2">
                        <div class="col-3">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Start Date') }}</label>
                            <input type="date" class="form-control form-control-sm" wire:model="start_date">
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.End Date') }}</label>
                            <input type="date" class="form-control form-control-sm" wire:model="end_date">
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Status') }}</label>
                            <select class="form-select form-select-sm" wire:model="status">
                                @foreach(\App\Support\Enum\BannerStatusEnum::cases() as $statusEnum)
                                    <option value="{{ $statusEnum->value }}">{{ $statusEnum->label() }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <label class="form-label fw-semibold small mb-1">{{ __('admin::lang.Priority') }}</label>
                            <input type="number" class="form-control form-control-sm" wire:model="priority" min="0" max="100">
                        </div>
                    </div>
                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-light" data-bs-dismiss="modal">{{ __('admin::lang.Cancel') }}</button>
                    <button type="submit" class="btn btn-sm btn-warning">
                        <i class="fe fe-save me-1"></i>{{ __('admin::lang.Update') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<style>
.search-result-item:hover {
    background-color: #f8f9fa;
}
</style>
