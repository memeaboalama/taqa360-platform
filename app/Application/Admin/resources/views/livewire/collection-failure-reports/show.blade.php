<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>@lang('admin::home.collection_failure_report_details') CFR-{{ $report->id }}</h4>
        <a href="{{ route('admin:collection-failure-reports.index') }}" class="btn btn-outline-secondary">@lang('admin::home.back_to_collection_failure_reports')</a>
    </div>
    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card custom-card">
                <div class="card-header"><h6 class="mb-0">@lang('admin::home.collection_failure_report_details')</h6></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">@lang('admin::home.order')</dt>
                        <dd class="col-sm-8"><a href="{{ route('admin:merchant.orders.showOrder', $report->order_id) }}">#{{ $report->order?->code }}</a></dd>
                        <dt class="col-sm-4">@lang('admin::home.driver_name')</dt>
                        <dd class="col-sm-8">{{ $report->driver?->name ?? '—' }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.amount_due')</dt>
                        <dd class="col-sm-8">{{ number_format($report->order?->total_after_discount > 0 ? $report->order->total_after_discount : ($report->order?->total_after_vat ?? 0), 2) }} SAR</dd>
                        <dt class="col-sm-4">@lang('admin::home.collection_failure_reason')</dt>
                        <dd class="col-sm-8">{{ $report->reason }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.collection_failure_note')</dt>
                        <dd class="col-sm-8">{{ $report->driver_note ?: '—' }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.created_at')</dt>
                        <dd class="col-sm-8">{{ $report->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.status')</dt>
                        <dd class="col-sm-8">@lang('admin::home.collection_failure_status_' . $report->status)</dd>
                        <dt class="col-sm-4">@lang('admin::home.payment_status')</dt>
                        <dd class="col-sm-8">{{ $report->order?->payment_label ?? __('ecommerce::ecommerce.unpaid') }}</dd>
                        @if($report->admin_instructions)
                            <dt class="col-sm-4">@lang('admin::home.collection_failure_instructions')</dt>
                            <dd class="col-sm-8">{{ $report->admin_instructions }}</dd>
                            <dt class="col-sm-4">@lang('admin::home.decision_by')</dt>
                            <dd class="col-sm-8">{{ $report->decider?->name ?? '—' }} · {{ $report->decided_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                        @endif
                    </dl>
                    @if($report->status === 'open')
                        <div class="mt-4">
                            <label class="form-label">@lang('admin::home.collection_failure_instructions')</label>
                            <textarea class="form-control mb-2" wire:model.defer="instructions" maxlength="2000" required></textarea>
                            @error('instructions') <span class="text-danger">{{ $message }}</span> @enderror
                            <button type="button" class="btn btn-success" wire:click="requestRetry" wire:loading.attr="disabled">@lang('admin::home.retry_collection')</button>
                            <button type="button" class="btn btn-warning" wire:click="requestReturn" wire:loading.attr="disabled">@lang('admin::home.direct_return')</button>
                        </div>
                    @endif
                    @error('report') <div class="alert alert-warning mt-3">{{ $message }}</div> @enderror
                </div>
            </div>
        </div>
        <div class="col-lg-5">
            <div class="card custom-card">
                <div class="card-header"><h6 class="mb-0">@lang('admin::home.collection_failure_history')</h6></div>
                <div class="card-body">
                    @forelse($report->histories as $history)
                        <div class="border-bottom pb-2 mb-2">
                            <strong>@lang('admin::home.collection_failure_action_' . $history->action)</strong>
                            <small class="text-muted d-block">{{ $history->actor?->name ?? '—' }} · {{ $history->created_at?->format('Y-m-d H:i') }}</small>
                            @if($history->note)<p class="mb-0 mt-1">{{ $history->note }}</p>@endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">@lang('admin::home.no_collection_failure_history')</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
