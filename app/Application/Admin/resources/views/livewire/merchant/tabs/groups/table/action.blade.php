<div>
    <div class="hstack gap-2 fs-15">
        <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill" wire:click="$emit('updateAdditionGroup',{{ $row->id }})" title="edit"><i class="ri-edit-line"></i></a>
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteAdditionGroup',{{ $row->id }})"><i class="ri-delete-bin-line"></i></a>
    </div>
</div>

