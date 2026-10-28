<div>
    <div class="hstack gap-2 fs-15">
        {{-- @AdminHasRole(['edit_coupons']) --}}
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading"
            data-loading-target="#updateMerchantCashback"
            wire:click="$emit('updateMerchantCashback',{{ $row->id }})"
            title="@lang('admin::home.Edit')"><i class="ri-edit-line"></i></a>
        {{-- @endAdminHasRole --}}

        {{-- @AdminHasRole(['delete_coupons']) --}}
        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-warning-transparent rounded-pill" title="{{ __('admin::home.deactivate') }}" wire:click="$emit('deleteMerchantCashback',{{ $row->id }})" ><i class="ri-toggle-line"></i></a>
        @endif
        {{-- @endAdminHasRole --}}
    </div>
</div>
