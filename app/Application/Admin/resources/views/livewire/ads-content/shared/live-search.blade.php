<div class="position-relative w-100">
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
            placeholder="{{ __('admin::lang.Search for', ['type' => ucfirst($searchType)]) }}..."
            wire:keydown.escape="clearSearch"
        >
    @endif

    @if(!empty($query) && count($results) > 0 && !$selectedItem)
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
