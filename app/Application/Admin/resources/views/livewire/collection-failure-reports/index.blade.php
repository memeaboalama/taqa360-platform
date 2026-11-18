<div class="card custom-card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('admin::home.collection_failure_reports')</h5>
    </div>
    <div class="card-body">
        <div class="row g-2 mb-3">
            <div class="col-md-5">
                <input class="form-control" wire:model.debounce.500ms="search" placeholder="@lang('admin::home.collection_failure_search')">
            </div>
            <div class="col-md-4">
                <select class="form-select" wire:model="driverId">
                    <option value="">@lang('admin::home.all_drivers')</option>
                    @foreach($drivers as $driver)
                        <option value="{{ $driver->id }}">{{ $driver->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <select class="form-select" wire:model="status">
                    <option value="">@lang('admin::home.all_collection_failure_statuses')</option>
                    @foreach(['open', 'retry_requested', 'return_requested', 'collection_succeeded', 'return_completed'] as $status)
                        <option value="{{ $status }}">@lang('admin::home.collection_failure_status_' . $status)</option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>@lang('admin::home.collection_failure_number')</th>
                        <th>@lang('admin::home.order')</th>
                        <th>@lang('admin::home.driver_name')</th>
                        <th>@lang('admin::home.amount_due')</th>
                        <th>@lang('admin::home.collection_failure_reason')</th>
                        <th>@lang('admin::home.collection_failure_note')</th>
                        <th>@lang('admin::home.created_at')</th>
                        <th>@lang('admin::home.status')</th>
                        <th>@lang('admin::home.review_action')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($reports as $report)
                        <tr>
                            <td>CFR-{{ $report->id }}</td>
                            <td>#{{ $report->order?->code }}</td>
                            <td>{{ $report->driver?->name ?? '—' }}</td>
                            <td>{{ number_format($report->order?->total_after_discount > 0 ? $report->order->total_after_discount : ($report->order?->total_after_vat ?? 0), 2) }} SAR</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->reason_label, 90) }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($report->driver_note, 90) ?: '—' }}</td>
                            <td>{{ $report->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>@lang('admin::home.collection_failure_status_' . $report->status)</td>
                            <td><a class="btn btn-sm btn-primary" href="{{ route('admin:collection-failure-reports.show', $report->id) }}">@lang('admin::home.review')</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted">@lang('admin::home.no_collection_failure_reports')</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $reports->links() }}
    </div>
</div>
