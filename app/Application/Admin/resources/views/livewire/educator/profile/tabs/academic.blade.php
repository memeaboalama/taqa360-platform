<div class="card custom-card">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">@lang('admin::educators.tabs.academics')</h5>

        @if($timelineItems)
            <div class="position-relative ps-4" style="border-right: 2px solid var(--bs-border-color);">
                @foreach($timelineItems as $item)
                    <div class="d-flex align-items-start mb-5" wire:key="{{ $item['type'] }}-{{ $item['id'] }}">
                        <div class="position-absolute timeline-icon {{ $item['type'] === 'academic' ? 'bg-info-subtle text-info' : 'bg-success-subtle text-success' }}" style="right: -21px; top: 0;">
                            @if($item['type'] === 'academic')
                                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.436 60.436 0 00-.491 6.347A48.627 48.627 0 0112 20.904a48.627 48.627 0 018.232-4.41 60.46 60.46 0 00-.491-6.347m-15.482 0a50.57 50.57 0 00-2.658-.813A59.905 59.905 0 0112 3.493a59.902 59.902 0 0110.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0l15.482 0m-15.482 0L12 10.5M12 10.5l1.5-1.5m-1.5 1.5l-1.5-1.5m1.5 1.5V5.625" /></svg>
                            @else
                                <svg class="icon-sm" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75L11.25 15 15 9.75m-3-7.036A11.959 11.959 0 013.598 6 11.99 11.99 0 003 9.749c0 5.592 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.62-3.75M12 5.036V12m0 0h3.75M12 12L8.25 5.036" /></svg>
                            @endif
                        </div>

                        <div class="card custom-card w-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <div>
                                        <h6 class="fw-bold mb-0">{{ $item['title'] }}</h6>
                                        <p class="small text-muted mb-0">{{ $item['subtitle'] }} @if($item['date']) - ({{ $item['date'] }}) @endif</p>
                                    </div>
                                    <div>
                                        @if($item['is_approved'])
                                            <span class="badge bg-success-subtle text-success-emphasis border border-success-subtle">@lang('admin::educators.approved')</span>
                                        @else
                                            <span class="badge bg-warning-subtle text-warning-emphasis border border-warning-subtle">@lang('admin::educators.pending_review')</span>
                                        @endif
                                    </div>
                                </div>
                                <p class="small text-muted border-top pt-2">{{ $item['description'] ?: __('admin::educators.no_description') }}</p>

                                @if(!empty($item['files']))
                                    <div class="mt-3">
                                        <h6 class="fw-bold small mb-2">@lang('admin::educators.attachments')</h6>
                                        <div class="d-flex flex-wrap gap-2">
                                            @foreach($item['files'] as $file)
                                            <a href="{{ data_get($file, 'url') }}" target="_blank" class="btn btn-sm btn-outline-secondary d-flex align-items-center gap-1">
                                                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" fill="currentColor" class="bi bi-paperclip" viewBox="0 0 16 16"><path d="M4.5 3a2.5 2.5 0 0 1 5 0v9a1.5 1.5 0 0 1-3 0V5a.5.5 0 0 1 1 0v7a.5.5 0 0 0 1 0V3a1.5 1.5 0 1 0-3 0v9a2.5 2.5 0 0 0 5 0V5a.5.5 0 0 1 1 0v7a3.5 3.5 0 1 1-7 0z"/></svg>
                                                <span class="text-truncate" style="max-width: 150px;">{{ data_get($file, 'original_name') }}</span>
                                            </a>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif
                            </div>
                            <div class="card-footer bg-light text-end">
                                <div class="d-flex justify-content-end gap-2">
                                    <button wire:click="setApprovalStatus('{{ $item['type'] }}', {{ $item['id'] }}, false)" wire:loading.attr="disabled" wire:target="setApprovalStatus" class="btn btn-sm btn-outline-danger d-inline-flex align-items-center gap-1" @if(!$item['is_approved']) disabled @endif>
                                        <svg wire:loading wire:target="setApprovalStatus('{{ $item['type'] }}', {{ $item['id'] }}, false)" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></svg>
                                        <span>@lang('admin::educators.reject')</span>
                                    </button>
                                    <button wire:click="setApprovalStatus('{{ $item['type'] }}', {{ $item['id'] }}, true)" wire:loading.attr="disabled" wire:target="setApprovalStatus" class="btn btn-sm btn-success d-inline-flex align-items-center gap-1" @if($item['is_approved']) disabled @endif>
                                        <svg wire:loading wire:target="setApprovalStatus('{{ $item['type'] }}', {{ $item['id'] }}, true)" class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></svg>
                                        <span>@lang('admin::educators.approve')</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <p class="text-muted text-center py-5">@lang('admin::educators.no_qualifications_added')</p>
        @endif
    </div>
</div>
