<div>
    <div class="hstack gap-2 fs-14">
       
        @if($row->elm_upload_status == 0)
            <span class="badge bg-success">@lang('admin::home.approved')</span>
        @elseif($row->elm_upload_status == 1 || $row->elm_upload_status == null)
            <span class="badge bg-warning">@lang('admin::home.pending')</span>
        @elseif($row->elm_upload_status == 2 )
            <span class="badge bg-warning">@lang('admin::home.Must update')</span>
        @elseif($row->elm_upload_status == 10)
            <a href="javascript:void(0);" class="" title="trash" wire:click="$emit('showElmErros',{{ $row->id }})">
                <span class="badge bg-danger">@lang('admin::home.Rejected')</span>
            </a>
        @endif    

    </div>
</div>
