<div>
    <div class="hstack gap-2 fs-15">
    @AdminHasRole(['customize coupons for restaurants_coupons'])
        @if($row->is_custom)
        <a
            href="{{ route('admin:cashBack.selectmerchants', $row->id) }}"
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            title="{{ __('admin::home.Customize') }}"><i class="ri-add-circle-fill"></i></a>
        @else
        &nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp&nbsp    
        @endif
        @endAdminHasRole

        @AdminHasRole(['edit_coupons'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            wire:click="$emit('updateCashBack',{{ $row->id }})"
            title="{{ __('admin::home.Edit') }}"
            onmousedown="this.querySelector('.btn-lbl').classList.add('invisible');this.querySelector('.btn-spin').classList.remove('d-none');var btn=this;setTimeout(function(){btn.querySelector('.btn-lbl').classList.remove('invisible');btn.querySelector('.btn-spin').classList.add('d-none');},2000);">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endAdminHasRole

        @AdminHasRole(['edit_coupons'])
        @if($row->status->value != \App\Support\Enum\StatusEnum::ACTIVE->value)
        <a href="javascript:void(0);" 
            class="btn btn-icon btn-sm btn-success-transparent rounded-pill"
            wire:click="$emit('enableCashBack',{{ $row->id}},{{$row->period}})"
            title="{{ __('admin::home.Enable') }}"><i class="ri-check-line"></i></a>
            @endif
        @endAdminHasRole
        
        @AdminHasRole(['delete_coupons'])
        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="{{ __('admin::home.Deactivate') }}" wire:click="$emit('deleteCashBack',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endif
        @endAdminHasRole
    </div>
</div>
