<div>
    <div class="hstack gap-2 fs-15">
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill"
            href="{{ route('admin:user.orders.show', ['user_id' => $row->user_id, 'id' => $row->id]) }}"
            title="show"><i class="ri-eye-2-line"></i></a>
    </div>
</div>
