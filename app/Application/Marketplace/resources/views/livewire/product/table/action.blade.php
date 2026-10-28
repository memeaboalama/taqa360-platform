<div>
    <div class="hstack gap-2 fs-15">
        {{-- @AdminHasRole(['edit_products']) --}}
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading"
            data-loading-target="#updateProduct"
            wire:click="$emit('updateProduct',{{ $row->id }})"
            title="@lang('marketplace::home.Edit')">
            <i class="ri-edit-line"></i>
        </a>
        {{-- @endAdminHasRole --}}

        {{-- @AdminHasRole(['view_products']) --}}
        <a
            class="btn btn-icon btn-sm btn-primary-transparent rounded-pill btn-data-loading"
            data-loading-target="#showProduct"
            wire:click="$emit('viewProduct',{{ $row->id }})"
            title="@lang('marketplace::home.show')">
            <i class="ri-eye-line"></i>
        </a>
        {{-- @endAdminHasRole --}}

        {{-- @AdminHasRole(['delete_products']) --}}
        <a href="javascript:void(0);"
           class="btn btn-icon btn-sm btn-danger-transparent rounded-pill"
           title="@lang('marketplace::home.delete')"
           wire:click="$emit('deleteProduct',{{ $row->id }})">
            <i class="ri-delete-bin-line"></i>
        </a>
        {{-- @endAdminHasRole --}}
    </div>
</div>