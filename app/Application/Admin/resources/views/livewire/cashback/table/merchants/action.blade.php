<div>
    <div class="hstack gap-2 fs-15">
 

       
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            wire:click="$emit('updateCashBackAdminMerchants',{{ $row->id }})"
            title="edit"><i class="ri-edit-line"></i></a>
        

        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteCashBack',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endif
    </div>
</div>
