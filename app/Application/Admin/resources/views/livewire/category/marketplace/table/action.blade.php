<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['edit_categories'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            wire:click="$emit('updateMarketplaceCategory',{{ $row->id }})"
            title="@lang('admin::home.Edit')"
            onmousedown="this.querySelector('.btn-lbl').classList.add('invisible');this.querySelector('.btn-spin').classList.remove('d-none');var btn=this;setTimeout(function(){btn.querySelector('.btn-lbl').classList.remove('invisible');btn.querySelector('.btn-spin').classList.add('d-none');},2000);">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endAdminHasRole

        @AdminHasRole(['delete_categories'])
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="@lang('admin::home.Delete')" wire:click="$emit('deleteMarketplaceCategory',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endAdminHasRole
    </div>
</div>
