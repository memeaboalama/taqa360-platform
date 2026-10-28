<div class="py-1">
    <button wire:click="export"
            @if(!$hasRecords) disabled @endif
            wire:loading.attr="disabled"
            wire:target="export"
            class="font-weight-bold text-uppercase px-3 py-2 rounded btn btn-success @if(!$hasRecords) disabled opacity-50 @endif"
            style="cursor: pointer">
        <span wire:loading.remove wire:target="export">
            {{ __('admin::home.Export') }} XLXS
        </span>
        <span wire:loading wire:target="export">
            {{ __('admin::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>
