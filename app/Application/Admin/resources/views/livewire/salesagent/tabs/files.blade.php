<div>
    <div class="row gy-3 mt-1">
        @forelse($files as $file)
            <div class="col-xl-12">
                <div class="card custom-card border-box-1 mb-0">
                    <div class="card-header">
                        <div class="card-title text-truncate">
                            {{ $file->original_name ?? $file->collection_name }}
                        </div>
                    </div>
                    <div class="card-body text-center">
                        @if($file->preview_url)
                            @if(in_array(strtolower($file->mime_type ?? ''), ['application/pdf', 'text/csv', 'application/vnd.ms-excel', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'text/plain']))
                                <a href="{{ $file->preview_url }}" target="_blank"
                                   class="btn btn-sm btn-primary-transparent"><i class="ri-eye-line align-middle me-1"></i>{{ __('admin::home.Files') }}</a>
                            @else
                                <div class="mx-auto" style="width:180px;">
                                    <a href="{{ $file->preview_url }}" target="_blank">
                                        <img src="{{ $file->preview_url }}"
                                             class="bd-placeholder-img figure-img img-fluid rounded card-img bg-light"
                                             alt="{{ $file->original_name ?? $file->collection_name }}">
                                    </a>
                                </div>
                            @endif
                        @else
                            <div class="text-muted">{{ __('admin::home.No file attached yet') }}</div>
                        @endif
                    </div>
                </div>
            </div>
        @empty
            <div class="col-xl-12">
                <div class="text-muted text-center py-4">{{ __('admin::home.No file attached yet') }}</div>
            </div>
        @endforelse
    </div>
</div>