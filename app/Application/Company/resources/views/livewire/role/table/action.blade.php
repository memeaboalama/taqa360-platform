<div>
    <div class="hstack gap-2 fs-15">
        @CompanyHasRole(['edit_roles'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading"
            data-loading-target="#updateRole"
            wire:click="$emit('updateRole',{{ $row->id }})"
            title="@lang('company::home.update')"><i class="ri-edit-line"></i></a>
        @endCompanyHasRole
    </div>
</div>
