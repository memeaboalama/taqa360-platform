<div class="hstack gap-2 fs-15">
    <a href="{{ route('admin:ad-management.banners.index', $row->id) }}" class="btn btn-icon btn-sm btn-info-light" title="{{ __('admin::lang.Manage Banners') }}">
        <i class="ri-image-line"></i>
    </a>
    <button wire:click="$dispatch('openUpdateModal', { placementId: {{ $row->id }} })" class="btn btn-icon btn-sm btn-warning-light" title="{{ __('admin::home.Edit') }}">
        <i class="ri-edit-line"></i>
    </button>
    <button wire:click="$dispatch('openDeleteModal', { placementId: {{ $row->id }} })" class="btn btn-icon btn-sm btn-danger-light" title="{{ __('admin::home.Delete') }}">
        <i class="ri-delete-bin-line"></i>
    </button>
</div>
