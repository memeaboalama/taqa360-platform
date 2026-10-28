<div>
    @AdminHasRole(['contact sender_messages'])
    <div class="hstack gap-2 fs-15">
        <div class="prism-toggle">
            <div class="custom-toggle-switch d-flex align-items-center mb-4 mt-3">
                <input id="active"
                       type="checkbox" {{ $row->status == "active"  ? 'checked' : '' }}>
                <label for="active" class="label-success"
                       wire:click.prevent="updateStatus('{{ $row->status == "active"  ? "inactive" : "active" }}', {{ $row->id }})" wire:ignore.self></label>
            </div>
        </div>

    </div>
    @endAdminHasRole
</div>
