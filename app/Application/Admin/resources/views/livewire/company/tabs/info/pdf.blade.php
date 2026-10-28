<div class="p-3 mb-1">

    <button type="submit" wire:click="exportPdf" class="font-weight-bold text-uppercase px-4 py-2 rounded btn btn-danger" style="cursor: pointer">
        <span class="indicator-label" wire:loading.class="d-none">
            {{ __('admin::home.Export') }} PDF
        </span>
        <span class="d-none" wire:loading.class.remove="d-none">
            {{ __('admin::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>
