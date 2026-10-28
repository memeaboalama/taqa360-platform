<div>
    <div class="hstack gap-2 fs-15">
        <a class="btn btn-icon btn-sm btn-info-transparent rounded-pill" href="{{ route('marketplace:showService', $row->id) }}" title="edit"><i class="ri-eye-2-line"></i></a>
        <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="trash" wire:click="$emit('deleteService',{{ $row->id }})"><i class="ri-delete-bin-line"></i></a>
    </div>
</div>
