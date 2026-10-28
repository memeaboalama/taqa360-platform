<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['edit_roles'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            href="{{ route('admin:roles.update', $row->id) }}"
            title="edit"
            onclick="this.querySelector('.btn-lbl').classList.add('invisible'); this.querySelector('.btn-spin').classList.remove('d-none'); this.classList.add('disabled');">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endAdminHasRole

        @AdminHasRole(['delete_roles'])
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteRole',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endAdminHasRole

    </div>
</div>
