<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['approve_drivers'])
        @if(!$row->is_complete)
            <a
                href="javascript:void(0);"
                class="btn btn-icon btn-sm btn-success-transparent rounded-pill"
                title="{{ __("admin::home.approve") }}" wire:click="$emit('approveDriver',{{ $row->id }})"><i class="ri-check-double-fill"></i></a>
            <a
                href="javascript:void(0);"
                class="btn btn-icon btn-sm btn-danger-transparent rounded-pill"
                title="{{ __("admin::home.reject") }}" wire:click="$emit('rejectDriver',{{ $row->id }})"><i class="ri-close-fill"></i></a>
        @endif
        @endAdminHasRole

        @AdminHasRole(['edit_drivers'])
        <a
            href="javascript:void(0);"
            class="btn btn-icon btn-sm btn-success-transparent rounded-pill position-relative"
            title="{{ __('admin::home.Actions Drivers To Day') }}"
            wire:click="$emit('showDriverHistory',{{ $row->id }})"
            wire:loading.attr="disabled"
            wire:target="showDriverHistory">
            <span wire:loading.remove wire:target="showDriverHistory"><i class="ri-file-history-fill"></i></span>
            <span class="position-absolute top-50 start-50 translate-middle" wire:loading wire:target="showDriverHistory">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endAdminHasRole

        @AdminHasRole(['edit_drivers'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            href="{{ url("admin/drivers/show/$row->id") }}"
            title="{{ __('admin::home.Edit') }}"
            onclick="this.querySelector('.btn-lbl').classList.add('invisible'); this.querySelector('.btn-spin').classList.remove('d-none'); this.classList.add('disabled');">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>

        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-warning-transparent rounded-pill"
           title="{{ __('admin::home.Update Password') }}"
           wire:click="$emit('resetDriverPassword',{{ $row->id }})"><i class="ri-lock-password-line"></i></a>
        @endAdminHasRole

        @AdminHasRole(['delete_drivers'])
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="{{ __('admin::home.Delete') }}" wire:click="$emit('deleteDriver',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endAdminHasRole

    </div>
</div>
