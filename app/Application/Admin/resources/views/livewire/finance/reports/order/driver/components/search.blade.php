<div>
    <div class="col-xl-12 mt-2">
        <input
            type="text"
            class="form-control"
            placeholder="{{ __('admin::home.Search Drivers by Name or phone ...') }}"
            style="width: 800px; margin-top: 20px"
            wire:model="query"
            wire:keydown.escape="resetSearch"
            wire:keydown.tab="resetSearch"
            wire:keydown.arrow-up="decrementHighlight"
            wire:keydown.arrow-down="incrementHighlight"
            wire:keydown.enter="selectDriver"
        />

        @if(!empty($query))
            <div class="fixed top-0 bottom-0 left-0 right-0" wire:click="resetSearch"></div>
            <div class="absolute z-10 list-group bg-white w-full rounded-t-none shadow-lg gy-3 p-2" style="overflow-y: scroll; height:400px;">
                @if($results->count() > 0)
                    @foreach($results as $key => $result)
                        <a
                            wire:click.prevent="$emit('selectDriver',{{ $result->id }})"
                            style="cursor: pointer";
                            class="p-2 mb-1 list-item {{ $highlightIndex === $key ? 'bg-primary' : '' }}"> {{ $result->name }} ({{ $result->phone }})</a>
                    @endforeach
                @else
                    <div class="list-item text-center mt-2"><h6>{{ __('admin::home.No results!') }}</h6></div>
                @endif
            </div>
        @endif
    </div>
</div>
