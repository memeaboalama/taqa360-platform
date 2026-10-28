<div>
    <div class="hstack gap-2 fs-15">
        @AdminHasRole(['show details_operating companies'])
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            href="{{ url('admin/companies/'.$row->id) }}"
            title="show"><i class="ri-eye-2-line"></i></a>
        @endAdminHasRole
    </div>
</div>
