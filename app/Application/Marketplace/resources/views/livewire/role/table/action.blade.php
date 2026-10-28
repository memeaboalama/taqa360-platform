<div>
    <div class="hstack gap-2 fs-15">
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading"
            data-loading-target="#updateRole"
            wire:click="$emit('updateRole',{{ $row->id }})"
            title="@lang('admin::home.Edit')"><i class="ri-edit-line"></i></a>
    </div>
</div>
