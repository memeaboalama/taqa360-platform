<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['show user Transaction_wallet'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            href="{{ route('admin:wallet.user.history', $row->id) }}"
            title="show"><i class="ri-eye-2-line"></i></a>
        @endAdminHasRole
    </div>
</div>
