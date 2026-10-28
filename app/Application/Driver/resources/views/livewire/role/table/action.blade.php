<div>
    <div class="hstack gap-2 fs-15">
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            wire:click="$emit('updateRole',{{ $row->id }})"
            title="edit"><i class="ri-edit-line"></i></a>
    </div>
</div>
