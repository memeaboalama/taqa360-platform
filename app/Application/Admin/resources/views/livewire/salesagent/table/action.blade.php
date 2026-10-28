<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['approve_salesagent'])
        @if(!$row->is_complete)
            <a
                href="javascript:void(0);"
                class="btn btn-icon btn-sm btn-success-transparent rounded-pill"
                title="{{ __("admin::home.approve") }}" wire:click="$emit('approveSalesAgent',{{ $row->id }})"><i class="ri-check-double-fill"></i></a>
        @endif
        @endAdminHasRole

        @AdminHasRole(['add_salesagents'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            href="{{ url("admin/salesagents/show/$row->id") }}"
            title="{{ __('admin::home.Edit') }}"
            onclick="this.querySelector('.btn-lbl').classList.add('invisible'); this.querySelector('.btn-spin').classList.remove('d-none'); this.classList.add('disabled');">
            <span class="btn-lbl"><i class="ri-edit-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endAdminHasRole

        @AdminHasRole(['add_salesagents'])
        <a
            href="javascript:void(0);"
            class="btn btn-icon btn-sm btn-warning-transparent rounded-pill"
            title="{{ __('admin::home.Reset Password') }}"
            wire:click="$emit('resetSalesAgentPassword',{{ $row->id }})"><i class="ri-lock-password-line"></i></a>
        @endAdminHasRole

        @AdminHasRole(['delete_salesagents'])
            <a href="javascript:void(0);" class="btn btn-icon btn-sm btn-danger-transparent rounded-pill" title="{{ __('admin::home.Delete') }}" wire:click="$emit('deleteSalseAgent',{{ $row->id }})" ><i class="ri-delete-bin-line"></i></a>
        @endAdminHasRole

    </div>
</div>
