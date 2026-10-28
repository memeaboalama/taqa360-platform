<div class="hstack gap-2 fs-15">
    <button wire:click="$dispatch('openUpdateModal', { bannerId: {{ $row->id }} })" class="btn btn-icon btn-sm btn-warning-light" title="{{ __('admin::home.Edit') }}">
        <i class="ri-edit-line"></i>
    </button>
    <button wire:click="$dispatch('openDeleteModal', { bannerId: {{ $row->id }} })" class="btn btn-icon btn-sm btn-danger-light" title="{{ __('admin::home.Delete') }}">
        <i class="ri-delete-bin-line"></i>
    </button>
</div>
