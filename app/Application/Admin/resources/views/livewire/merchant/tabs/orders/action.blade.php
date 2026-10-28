<div>
    <div class="hstack gap-2 fs-15">
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            href="{{ url("admin/merchants/$row->merchant_id/orders/$row->id") }}"
            title="show"><i class="ri-eye-2-line"></i></a>

        @if($row->status == "started-work" || $row->status == "confirmed")
            <a
                href="javascript:void(0);"
                class="btn btn-danger-transparent btn-sm"
                title="Cancel"
                wire:click="$emit('cancelOrder',{{ $row->id }})">
                Cancel</a>
        @endif

    </div>
</div>
