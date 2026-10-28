<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['customize coupons for restaurants_coupons'])
        @if($row->is_custom)
        <a
            href="{{ route('admin:coupons.selectmerchants', $row->id) }}"
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            {{-- wire:click="$emit('selectmerchants',{{ $row->id }})" --}}
            title="edit"><i class="ri-add-circle-fill"></i></a>
        @else
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp    
        @endif
        @endAdminHasRole

        @AdminHasRole(['edit_coupons'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            wire:click="$emit('updateOffer',{{ $row->id }})"
            title="edit"><i class="ri-edit-line"></i></a>
        @endAdminHasRole
        @AdminHasRole(['edit_coupons'])
        @if($row->status->value != \App\Support\Enum\StatusEnum::ACTIVE->value)
        <a href="javascript:void(0);" 
            class="btn btn-icon btn-sm btn-success-transparent rounded-pill"
            wire:click="$emit('enableOffer',{{ $row->id}},{{$row->period}})"
            title="enable"><i class="ri-check-line"></i></a>
            @endif
        @endAdminHasRole

        @AdminHasRole(['delete_coupons'])
        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" 
            class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" 
            title="trash" wire:click="$emit('deleteOffer',{{ $row->id }})" >
            <i class="ri-delete-bin-line"></i></a>
        @endif
        @endAdminHasRole
    </div>
</div>
