<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['edit_merchant packages'])
        <a
            href="javascript:void(0);"
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            wire:click="$emit('editPackage',{{ $row->id }})"
            title="@lang('admin::lang.Edit')" data-edit-loading><i class="ri-edit-line"></i></a>
        @endAdminHasRole

        @AdminHasRole(['delete_merchant packages'])
        @if($row->status->value == \App\Support\Enum\StatusEnum::ACTIVE->value)
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="@lang('admin::lang.Delete')" wire:click="$emit('deletePackage',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endif
        @endAdminHasRole
    </div>
</div>
