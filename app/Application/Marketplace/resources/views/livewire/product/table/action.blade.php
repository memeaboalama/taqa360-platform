<div>
    <div class="hstack gap-2 fs-15">
        {{-- @AdminHasRole(['edit_products']) --}}
        @if(auth('merchant')->check())
        <a href="{{ route('marketplace:products.edit', $row->id) }}"
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            title="@lang('marketplace::home.Edit')">
            <i class="ri-edit-line"></i>
        </a>
        @else
        <button type="button" class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading"
            data-loading-target="#updateProduct" wire:click="$emit('updateProduct',{{ $row->id }})"
            title="@lang('marketplace::home.Edit')"><i class="ri-edit-line"></i></button>
        @endif
        {{-- @endAdminHasRole --}}

        {{-- @AdminHasRole(['view_products']) --}}
        <button type="button"
            class="btn btn-icon btn-sm btn-primary-transparent rounded-pill btn-data-loading"
            data-loading-target="#showProduct"
            wire:click="$emit('viewProduct',{{ $row->id }})"
            title="@lang('marketplace::home.View details')">
            <i class="ri-eye-line"></i>
        </button>
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
