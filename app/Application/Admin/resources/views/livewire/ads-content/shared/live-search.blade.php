<div class="position-relative w-100 {{ $lazyLoad ? 'ads-content-live-search' : '' }}">
    @once
        <style>
            .ads-content-live-search:not(:focus-within) .ads-content-live-search__dropdown {
                display: none !important;
            }
        </style>
    @endonce

    @if($selectedItem)
        <div class="form-control form-control-sm d-flex align-items-center justify-content-between p-1 ps-2 bg-white" style="height: 38px;">
            <div class="d-flex align-items-center overflow-hidden">
                @if(isset($selectedItem['image']) && $selectedItem['image'])
                    <img src="{{ asset($selectedItem['image']) }}" alt="" class="rounded-circle me-2" style="width: 24px; height: 24px; object-fit: cover;">
                @endif
                <span class="text-truncate fw-semibold small">{{ $selectedItem['title'] ?? 'N/A' }}</span>
            </div>
            <button type="button" wire:click="clearSelection" class="btn btn-link text-danger p-0 me-2" style="font-size: 14px;">
                <i class="fe fe-x"></i>
            </button>
        </div>
    @else
        <input
            type="text"
            class="form-control form-control-sm"
            style="height: 38px;"
            wire:model.debounce.300ms="query"
            placeholder="{{ $disabled ? __('admin::lang.Select a merchant first') : __('admin::lang.Search for', ['type' => ucfirst($searchType)]) . '...' }}"
            wire:keydown.escape="clearSearch"
            @if($disabled) disabled @endif
        >
    @endif

    @if($lazyLoad)
        @if(!$selectedItem && count($results) > 0)
            <div
                class="ads-content-live-search__dropdown position-absolute bg-white border rounded shadow-lg w-100 mt-1"
                style="max-height: 250px; overflow-y: auto; z-index: 1050;"
                x-on:scroll.debounce.150ms="if ($el.scrollTop + $el.clientHeight >= $el.scrollHeight - 20) { $wire.loadMore() }"
            >
                @foreach($results as $index => $result)
                    <a
                        href="javascript:void(0)"
                        class="dropdown-item d-flex align-items-center p-2 border-bottom"
                        wire:click.prevent="select({{ $index }})"
                        style="cursor: pointer;"
                    >
                        @if(isset($result['image']) && $result['image'])
                            <img src="{{ $result['image'] }}" alt="" class="rounded me-2" style="width: 35px; height: 35px; object-fit: cover;">
                        @endif
                        <div class="flex-grow-1 overflow-hidden">
                            <div class="fw-semibold small text-truncate">{{ $result['title'] ?? 'N/A' }}</div>
                            @if(!empty($result['level_label']))
                                <small class="text-muted">{{ __('admin::lang.' . $result['level_label']) }}</small>
                            @endif
                        </div>
                    </a>
                @endforeach
                <div wire:loading wire:target="loadMore" class="text-center p-2">
                    <small class="text-muted">{{ __('admin::lang.Loading') }}...</small>
                </div>
            </div>
        @elseif(!$selectedItem && count($results) === 0 && ($query === '' || strlen($query) >= 2))
            <div class="ads-content-live-search__dropdown position-absolute bg-white border rounded shadow-lg w-100 mt-1 p-2 text-center" style="z-index: 1050;">
                <small class="text-muted">{{ __('admin::lang.No results!') }}</small>
            </div>
        @endif
    @elseif(!empty($query) && count($results) > 0 && !$selectedItem)
        <div class="position-absolute bg-white border rounded shadow-lg w-100 mt-1" style="max-height: 250px; overflow-y: auto; z-index: 1050;">
            @foreach($results as $index => $result)
                <a
                    href="javascript:void(0)"
                    class="dropdown-item d-flex align-items-center p-2 border-bottom"
                    wire:click.prevent="select({{ $index }})"
                    style="cursor: pointer;"
                >
                    @if(isset($result['image']) && $result['image'])
                        <img src="{{ $result['image'] }}" alt="" class="rounded me-2" style="width: 35px; height: 35px; object-fit: cover;">
                    @endif
                    <div class="flex-grow-1 overflow-hidden">
                        <div class="fw-semibold small text-truncate">{{ $result['title'] ?? 'N/A' }}</div>
                    </div>
                </a>
            @endforeach
        </div>
    @elseif(!empty($query) && count($results) === 0 && strlen($query) >= 2 && !$selectedItem)
        <div class="position-absolute bg-white border rounded shadow-lg w-100 mt-1 p-2 text-center" style="z-index: 1050;">
            <small class="text-muted">{{ __('admin::lang.No results!') }}</small>
        </div>
    @endif
</div>
