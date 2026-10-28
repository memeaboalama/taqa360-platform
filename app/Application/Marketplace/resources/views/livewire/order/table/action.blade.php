<div>
    <div class="hstack gap-2 fs-15">
        <a
            class="btn btn-icon btn-sm btn-info-transparent rounded-pill btn-data-loading-nav"
            href="{{ route('marketplace:showOrder', $row->id) }}"
            title="@lang('marketplace::home.show')" ><i class="ri-eye-2-line"></i></a>

        @php
            $statusVal = $row->status instanceof \UnitEnum ? $row->status->value : $row->status;
        @endphp
        @if(in_array($statusVal, ['pending', 'started-work', 'confirmed']))
            <a
                href="javascript:void(0);"
                class="btn btn-sm btn-danger-transparent"
                title="إلغاء الطلب"
                wire:click="$emit('cancelOrder',{{ $row->id }})" >
                <i class="ri-close-circle-line me-1"></i> إلغاء
            </a>
        @endif

    </div>
</div>
