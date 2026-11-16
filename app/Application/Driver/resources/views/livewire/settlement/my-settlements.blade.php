<div class="card custom-card">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('driver::home.my_settlements')</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-bordered align-middle">
                <thead>
                    <tr>
                        <th>@lang('driver::home.settlement_number')</th>
                        <th>@lang('driver::home.settlement_order')</th>
                        <th>@lang('driver::home.settlement_amount')</th>
                        <th>@lang('driver::home.supply_method')</th>
                        <th>@lang('driver::home.submitted_at')</th>
                        <th>@lang('driver::home.settlement_status')</th>
                        <th>@lang('driver::home.latest_update')</th>
                        <th>@lang('driver::home.actions')</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($settlements as $settlement)
                        @php($latestHistory = $settlement->histories->last())
                        @php($lastSubmission = $settlement->histories->whereIn('action', ['submitted', 'resubmitted'])->last())
                        <tr>
                            <td>STL-{{ $settlement->id }}</td>
                            <td>#{{ $settlement->order?->code }}</td>
                            <td>{{ number_format($settlement->amount, 2) }} SAR</td>
                            <td>@lang('driver::home.supply_method_' . $settlement->method)</td>
                            <td>{{ ($lastSubmission?->created_at ?? $settlement->created_at)?->format('Y-m-d H:i') ?? '—' }}</td>
                            <td>
                                <span class="badge {{ $settlement->status === 'approved' ? 'bg-success' : ($settlement->status === 'rejected' ? 'bg-danger' : ($settlement->status === 'returned' ? 'bg-warning' : 'bg-info')) }}">
                                    @lang('driver::home.settlement_status_' . $settlement->status)
                                </span>
                            </td>
                            <td>
                                @if($latestHistory)
                                    @lang('driver::home.settlement_action_' . $latestHistory->action)
                                    <small class="text-muted d-block">{{ $latestHistory->created_at?->format('Y-m-d H:i') }}</small>
                                    @if($latestHistory->note)
                                        <span class="text-danger d-block">{{ $latestHistory->note }}</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="Livewire.emit('showOrderDetails', {{ $settlement->order_id }})">
                                    @lang('driver::home.view_order')
                                </button>
                                @if($settlement->status === 'returned')
                                    <button type="button" class="btn btn-sm btn-warning"
                                            onclick="Livewire.emit('showOrderDetails', {{ $settlement->order_id }})">
                                        @lang('driver::home.complete_and_resubmit')
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted">@lang('driver::home.no_settlements')</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        {{ $settlements->links() }}
    </div>
</div>
