<div>
    <div class="hstack gap-2 fs-15">
        <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading-nav"
            href="{{ url('merchant/branches/'.$row->id) }}"
            title="@lang('marketplace::home.show')"><i class="ri-eye-2-line"></i></a>

       {{-- @MerchantHasRole(['delete_branches'])--}}
{{--        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash"--}}
{{--           wire:click="$emit('deleteBranch',{{ $row->id }})"><i class="ri-delete-bin-line"></i></a>--}}
{{--        @endMerchantHasRole --}}
    </div>
</div>
