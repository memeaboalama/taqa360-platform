<div>
    <span class="badge {{ $row->status === 'active' ? 'bg-success' : 'bg-secondary' }}">
        {{ $row->status === 'active' ? __('admin::home.contacted') : __('admin::home.not_contacted') }}
    </span>

    @AdminHasRole(['contact sender_messages'])
    <div class="hstack gap-2 fs-15 mt-1">
        <div class="prism-toggle">
            <div class="custom-toggle-switch d-flex align-items-center">
                <input id="message-status-{{ $row->id }}"
                       type="checkbox"
                       aria-label="{{ __('admin::home.contacted') }}"
                       {{ $row->status === 'active' ? 'checked' : '' }}>
                <label for="message-status-{{ $row->id }}" class="label-success"
                       wire:click.prevent="updateStatus('{{ $row->status === 'active' ? 'inactive' : 'active' }}', {{ $row->id }})"
                       wire:ignore.self></label>
            </div>
        </div>
    </div>
    @endAdminHasRole
</div>
