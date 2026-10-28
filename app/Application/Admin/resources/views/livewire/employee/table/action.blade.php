<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['edit_employees'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            wire:click="$emit('updateEmployee',{{ $row->id }})"
            title="{{ __('admin::home.Edit') }}"
            onmousedown="this.querySelector('.btn-lbl').classList.add('invisible');this.querySelector('.btn-spin').classList.remove('d-none');var btn=this;setTimeout(function(){btn.querySelector('.btn-lbl').classList.remove('invisible');btn.querySelector('.btn-spin').classList.add('d-none');},2000);">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-warning-transparent rounded-pill"
           title="{{ __('admin::home.reset_password') }}"
           wire:click="$emit('resetEmployeePassword',{{ $row->id }})"><i class="ri-lock-password-line"></i></a>
        @endAdminHasRole

        @AdminHasRole(['delete_employees'])
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="{{ __('admin::home.Delete') }}" wire:click="$emit('deleteEmployee',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endAdminHasRole
    </div>
</div>
