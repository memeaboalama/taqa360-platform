<div>
    <div class="hstack gap-2 fs-15">
        <a class="btn btn-sm btn-info-transparent btn-data-loading-nav d-inline-flex align-items-center gap-1"
            href="{{ route('marketplace:branches.show', ['id' => $row->id]) }}"
            title="@lang('marketplace::home.Manage')">
            <i class="ri-eye-2-line"></i>
            <span>@lang('marketplace::home.Manage')</span>
        </a>

       {{-- @MerchantHasRole(['delete_branches'])--}}
{{--        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash"--}}
{{--           wire:click="$emit('deleteBranch',{{ $row->id }})"><i class="ri-delete-bin-line"></i></a>--}}
{{--        @endMerchantHasRole --}}
    </div>
</div>
