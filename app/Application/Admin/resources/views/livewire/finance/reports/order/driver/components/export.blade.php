<div class="p-3 mb-1" >
    <button type="button"
            wire:click="export"
            wire:loading.attr="disabled"
            wire:target="export"
            class="font-weight-bold text-uppercase px-4 py-2 rounded btn btn-success"
            style="cursor: pointer">
        <span wire:loading.remove wire:target="export">
            {{ __('admin::home.Export') }} XLXS
        </span>
        <span wire:loading wire:target="export">
            {{ __('admin::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>
