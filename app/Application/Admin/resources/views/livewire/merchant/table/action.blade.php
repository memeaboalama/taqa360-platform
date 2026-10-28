<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['show details_merchants'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            href="{{ isset($pending) && $pending ? url('admin/merchants/pending/'.$row->id) : url('admin/merchants/'.$row->id) }}"
            title="{{ __('admin::home.show') }}" data-edit-loading><i class="ri-eye-2-line"></i></a>
        @endAdminHasRole

        @AdminHasRole(['activate_merchants'])
            @if(!$row->merchantActive() && $row->status == "inactive" || $row->status == null)
                <a href="javascript:void(0);" class="btn btn-sm btn-success-transparent rounded-pill" title="{{ __('admin::home.activate') }}"
                   wire:click="$emit('activateMerchant',{{ $row->id }})"><i class="ri-check-line"></i> {{ __('admin::home.activate') }}</a>
            @endif


            @if($row->status != "inactive")
                <a href="javascript:void(0);" class="btn btn-sm btn-danger-transparent rounded-pill" title="{{ __('admin::home.deactivate') }}"
                   wire:click="$emit('deactivateMerchant',{{ $row->id }})"><i class="ri-delete-bin-2-line"></i> {{ __('admin::home.deactivate') }}</a>
            @endif

            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-warning-transparent rounded-pill" title="{{ __('admin::home.reset_password') }}"
               wire:click="$emit('resetMerchantPassword',{{ $row->id }})"><i class="ri-lock-password-line"></i></a>
        @endAdminHasRole
    </div>
</div>
