<div>
    <div class="card custom-card">
        <div class="card-header bg-white"><h5 class="fw-bold mb-0">@lang('admin::educators.review_documents')</h5></div>
        <div class="card-body p-4">

            @if($profile)
            <div class="border rounded-3 p-4 mb-4 bg-light">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div>
                        <h6 class="fw-bold mb-0">@lang('admin::educators.review_identity')</h6>
                        <p class="small text-muted mb-0">
                            @lang('admin::educators.identity_type_label'): {{ $profile->identity_type === 'national_id' ? __('admin::educators.identity_national_id') : __('admin::educators.identity_passport') }} |
                            @lang('admin::educators.identity_number_label'): {{ $profile->identity_id }}
                        </p>
                    </div>
                    <span class="badge {{ $profile->identity_status->color() }}">{{ $profile->identity_status->trans() }}</span>
                </div>

                @if($identityFiles)
                <div class="row g-3">
                    @foreach($identityFiles as $file)
                    <div class="col-md-6">
                        <div class="card h-100 shadow-none">
                            <a href="{{ $file->url }}" target="_blank" class="d-block text-center p-2">
                                <img src="{{ $file->url }}" class="card-img-top" style="height: 150px; object-fit: contain;" alt="{{ $file->type }}">
                            </a>
                            <div class="card-body py-2 px-3">
                                <h6 class="card-title small fw-bold text-truncate">{{ $this->getCollectionLabel($file->collection_name) }}</h6>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                    <p class="text-muted text-center small py-3">@lang('admin::educators.no_identity_images')</p>
                @endif

                <div class="d-flex justify-content-end gap-2 mt-3 pt-3 border-top">
                    <button wire:click="setIdentityStatus('rejected')" wire:loading.attr="disabled" wire:target="setIdentityStatus" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" @if($profile->identity_status->value === 'rejected') disabled @endif>
                        <svg wire:loading wire:target="setIdentityStatus('rejected')" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></svg>
                        <span>@lang('admin::educators.reject')</span>
                    </button>
                    <button wire:click="setIdentityStatus('verified')" wire:loading.attr="disabled" wire:target="setIdentityStatus" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1" @if($profile->identity_status->value === 'verified') disabled @endif>
                        <svg wire:loading wire:target="setIdentityStatus('verified')" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></svg>
                        <span>@lang('admin::educators.approve')</span>
                    </button>
                </div>
            </div>
            @endif

            <h6 class="fw-bold mb-3 mt-5">@lang('admin::educators.other_documents')</h6>
            @if($otherFiles->isNotEmpty())
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>@lang('admin::educators.file_type')</th><th>@lang('admin::educators.original_name')</th><th>@lang('admin::educators.upload_date')</th><th class="text-end">@lang('admin::educators.action')</th></tr></thead>
                    <tbody>
                        @foreach($otherFiles as $file)
                        <tr>
                            <td><span class="badge bg-secondary-subtle text-secondary-emphasis">{{ $this->getCollectionLabel($file->collection_name ?: $file->type) }}</span></td>
                            <td>{{ $file->original_name }}</td>
                            <td class="text-muted small">{{ $file->created_at->format('Y-m-d') }}</td>
                            <td class="text-end"><a href="{{ $file->url }}" target="_blank" class="btn btn-sm btn-outline-primary">@lang('admin::educators.view_upload')</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            @else
                <p class="text-muted text-center py-5">@lang('admin::educators.no_documents_uploaded')</p>
            @endif
        </div>
    </div>
</div>
