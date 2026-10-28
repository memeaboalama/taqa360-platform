<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['show details_customers'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill position-relative"
            href="{{ route('admin:user.show', $row->id) }}"
            title="{{ __('admin::home.show') }}"
            onclick="this.querySelector('.btn-lbl').classList.add('invisible'); this.querySelector('.btn-spin').classList.remove('d-none'); this.classList.add('disabled');">
            <span class="btn-lbl"><i class="ri-eye-2-line"></i></span>
            <span class="btn-spin d-none position-absolute top-50 start-50 translate-middle">
                <span class="spinner-border" style="width:0.75rem;height:0.75rem;border-width:2px;"></span>
            </span>
        </a>
        @endAdminHasRole
    </div>
</div>
