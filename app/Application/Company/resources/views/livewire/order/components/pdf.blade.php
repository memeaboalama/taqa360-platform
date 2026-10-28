<div class="p-3 mb-1">
    <button type="button"
            wire:click="exportPdf"
            @if(!$hasRecords) disabled @endif
            wire:loading.attr="disabled"
            wire:target="exportPdf"
            class="font-weight-bold text-uppercase px-4 py-2 rounded btn btn-danger @if(!$hasRecords) disabled opacity-50 @endif"
            style="cursor: pointer">
        <span class="indicator-label" wire:loading.class="d-none" wire:target="exportPdf">
            {{ __('company::home.Export') }} PDF
        </span>
        <span class="d-none" wire:loading.class.remove="d-none" wire:target="exportPdf">
            {{ __('company::home.Wait') }} <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
    </button>
</div>