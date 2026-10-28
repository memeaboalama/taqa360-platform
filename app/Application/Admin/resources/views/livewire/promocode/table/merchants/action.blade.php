<div>
    <div class="hstack gap-2 fs-15">
        {{-- @AdminHasRole(['edit_coupons'])
        @if($row->is_custom)
        <a
            wire:click="$emit('createPromocodeAdminMerchants',{{ $row->id }})"
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            title="edit"><i class="ri-add-circle-fill"></i></a>
        @else
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp    
        @endif
        @endAdminHasRole --}}

        @AdminHasRole(['edit_merchant coupons'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            wire:click="$emit('updatePromocodeAdminMerchants',{{ $row->id }})"
            title="edit"><i class="ri-edit-line"></i></a>
        @endAdminHasRole

        @AdminHasRole(['delete_merchant coupons'])
        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deletePromocode',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endif
        @endAdminHasRole
    </div>
</div>
