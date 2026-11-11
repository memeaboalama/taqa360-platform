<div>
    <div class="hstack gap-2 fs-15">
 

       
        @AdminHasRole(['edit_merchant cashbacks', 'edit_merchant coupons', 'edit_coupons'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            wire:click="$emit('updateCashBackAdminMerchants',{{ $row->id }})"
            title="edit"><i class="ri-edit-line"></i></a>
        @endAdminHasRole
        

        @AdminHasRole(['delete_merchant cashbacks', 'delete_merchant coupons', 'delete_coupons'])
        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteCashBack',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endif
        @endAdminHasRole
    </div>
</div>
