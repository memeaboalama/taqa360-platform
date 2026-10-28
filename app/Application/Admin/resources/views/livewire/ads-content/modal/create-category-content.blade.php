<div class="modal fade" id="createCategoryContentModal" tabindex="-1" aria-hidden="true" wire:ignore.self>
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header bg-primary-transparent">
                <h6 class="modal-title fw-bold text-primary">
                    <i class="fe fe-plus-circle me-2"></i>{{ __('admin::lang.Add Categories') }}
                </h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>

            <form wire:submit.prevent="store">
                <div class="modal-body p-3">
                    
                    <div class="mb-3">
                        <label class="form-label small">{{ __('admin::lang.Search Categories') }}</label>
                        <input type="text" class="form-control form-control-sm" wire:model.debounce.300ms="searchTerm" placeholder="{{ __('admin::lang.Type to search...') }}">
                    </div>

                    <div class="category-list border rounded p-2" style="max-height: 300px; overflow-y: auto;">
                        @forelse($categories as $category)
                            <div class="form-check mb-2">
                                <input class="form-check-input" type="checkbox" value="{{ $category->id }}" id="cat-{{ $category->id }}" wire:model="selectedCategories">
                                <label class="form-check-label d-flex align-items-center gap-2" for="cat-{{ $category->id }}" style="cursor: pointer;">
                                    @if($category->image)
                                        <img src="{{ $category->image }}" width="30" height="30" class="rounded object-fit-cover" alt="">
                                    @else
                                        <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 30px; height: 30px;">
                                            <i class="bi bi-image text-muted"></i>
                                        </div>
                                    @endif
                                    <span>{{ $category->title }}</span>
                                </label>
                            </div>
                        @empty
                            <div class="text-center text-muted py-3">
                                <small>{{ __('admin::lang.No categories found') }}</small>
                            </div>
                        @endforelse
                    </div>
                    
                    <div class="mt-2 text-end">
                        <small class="text-muted">{{ count($selectedCategories) }} {{ __('admin::lang.selected') }}</small>
                    </div>

                </div>

                <div class="modal-footer bg-light py-2">
                    <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">{{ __('admin::home.Close') }}</button>
                    <button type="submit" class="btn btn-sm btn-primary" @if(empty($selectedCategories)) disabled @endif>
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
