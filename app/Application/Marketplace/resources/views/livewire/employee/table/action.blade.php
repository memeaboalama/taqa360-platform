<div>
    <div class="hstack gap-2 fs-15">
        @MerchantHasRole(['edit_employees'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading"
            data-loading-target="#updateEmployee"
            wire:click="$emit('updateEmployee',{{ $row->id }})"
            title="@lang('admin::home.Edit')"><i class="ri-edit-line"></i></a>
        <a
            class="btn btn-icon btn-sm btn-warning-transparent rounded-pill btn-data-loading"
            data-loading-target="#updatePasswordEmployee"
            wire:click="$emit('updatePasswordEmployee',{{ $row->id }})"
            title="update password"><i class="ri-lock-password-line"></i></a>
        @endMerchantHasRole

        @MerchantHasRole(['delete_employees'])
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteEmployee',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endMerchantHasRole
    </div>
</div>
