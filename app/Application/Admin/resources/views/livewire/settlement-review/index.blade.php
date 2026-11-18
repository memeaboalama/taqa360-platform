<div class="card custom-card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('admin::home.driver_settlements')</h5>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-3">
                <input class="form-control" wire:model.debounce.500ms="search"
                       placeholder="@lang('admin::home.search_settlement_or_order')">
            </div>
            <div class="col-md-2">
                <select class="form-select" wire:model="driverId">
                    <option value="">@lang('admin::home.all_drivers')</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" wire:model="from" aria-label="@lang('admin::home.custody_from_date')">
            </div>
            <div class="col-md-2">
                <input type="date" class="form-control" wire:model="to" aria-label="@lang('admin::home.custody_to_date')">
            </div>
            <div class="col-md-3">
                <select class="form-select" wire:model="status">
                    <option value="">@lang('admin::home.all_settlement_statuses')</option>
                    <option value="pending_review">@lang('admin::home.settlement_status_submitted')</option>
                    <option value="returned">@lang('admin::home.settlement_status_returned')</option>
                    <option value="rejected">@lang('admin::home.settlement_status_rejected')</option>
                    <option value="approved">@lang('admin::home.settlement_status_approved')</option>
                    <option value="reviewed">@lang('admin::home.settlement_status_reviewed')</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>@lang('admin::home.settlement_number')</th>
                        <th>@lang('admin::home.driver_name')</th>
                        <th>@lang('admin::home.recipient')</th>
                        <th>@lang('admin::home.submitted_at')</th>
                        <th>@lang('admin::home.total_amount')</th>
                        <th>@lang('admin::home.orders_count')</th>
                        <th>@lang('admin::home.supply_method')</th>
                        <th>@lang('admin::home.status')</th>
                        <th>@lang('admin::home.review_action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlements as $settlement)
                        <tr>
                            @php($lastSubmission = $settlement->histories->whereIn('action', ['submitted', 'resubmitted'])->last())
                            <td>
                                STL-{{ $settlement->batch_number }}
                                @foreach($settlement->batch_orders as $orderCode)
                                    <span class="text-muted d-block">#{{ $orderCode }}</span>
                                @endforeach
                            </td>
                            <td>{{ $settlement->driver?->name ?? '—' }}</td>
                            <td>{{ __('admin::home.' . ($settlement->recipient === 'merchant' ? 'merchant_recipient' : 'platform_recipient')) }}</td>
                            <td>{{ ($lastSubmission?->created_at ?? $settlement->created_at)?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>{{ number_format($settlement->batch_amount, 2) }} SAR</td>
                            <td>{{ $settlement->batch_orders_count }}</td>
                            <td>{{ __('admin::home.supply_method_' . $settlement->method) }}</td>
                            <td>
                                <span class="badge {{ $settlement->status === 'approved' ? 'bg-success' : (in_array($settlement->status, ['rejected'], true) ? 'bg-danger' : ($settlement->status === 'returned' ? 'bg-warning' : 'bg-info')) }}">
                                    @lang('admin::home.settlement_status_' . $settlement->status)
                                </span>
                            </td>
                            <td>
                                <a class="btn btn-sm btn-primary"
                                   href="{{ route('admin:driver-settlements.show', $settlement->id) }}">
                                    @lang('admin::home.review')
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">@lang('admin::home.no_settlements_found')</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $settlements->links() }}
    </div>
</div>
