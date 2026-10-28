<div class="d-flex gap-1">
    <button class="btn btn-sm btn-success" 
            wire:click="$dispatch('attemptReactivation', { bannerId: {{ $banner->id }} })"
            title="{{ __('admin::lang.Reactivate') }}">
        <i class="fe fe-rotate-ccw"></i>
    </button>
    <button class="btn btn-sm btn-info" 
            wire:click="$dispatch('openUpdateModal', { bannerId: {{ $banner->id }} })"
            title="{{ __('admin::lang.View Details') }}">
        <i class="fe fe-eye"></i>
    </button>
</div>
