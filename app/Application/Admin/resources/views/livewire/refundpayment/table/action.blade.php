<div>
    <div class="hstack gap-2 fs-15">
    


        <!-- @AdminHasRole(['show_refundpayments']) -->
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            href="{{ url("admin/refundpayments/show/$row->id") }}"
            title="show"><i class="ri-edit-line"></i></a>
        <!-- @endAdminHasRole -->

    </div>
</div>
