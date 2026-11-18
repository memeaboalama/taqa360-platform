<div class="card custom-card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">@lang('driver::home.my_settlements')</h5>
            <button type="button" class="btn btn-primary"
                    onclick="Livewire.emit('openDriverSettlementForm')">
                @lang('driver::home.create_settlement_request')
            </button>
        </div>
    </div>
    <div class="card-body">
        <form class="row g-2 align-items-end mb-3" wire:submit.prevent="applyFilters">
            <div class="col-md-3">
                <label class="form-label">@lang('driver::home.settlement_status')</label>
                <select class="form-select" wire:model.defer="statusFilter">
                    <option value="">@lang('driver::home.all_settlement_statuses')</option>
                    <option value="pending_review">@lang('driver::home.settlement_status_pending_review')</option>
                    <option value="reviewed">@lang('driver::home.settlement_status_reviewed')</option>
                    <option value="returned">@lang('driver::home.settlement_status_returned')</option>
                    <option value="rejected">@lang('driver::home.settlement_status_rejected')</option>
                    <option value="approved">@lang('driver::home.settlement_status_approved')</option>
                </select>
                @error('statusFilter') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">@lang('driver::home.date_from')</label>
                <input type="date" class="form-control" wire:model.defer="dateFrom">
                @error('dateFrom') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-3">
                <label class="form-label">@lang('driver::home.date_to')</label>
                <input type="date" class="form-control" wire:model.defer="dateTo">
                @error('dateTo') <span class="text-danger">{{ $message }}</span> @enderror
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-outline-primary">@lang('driver::home.apply_filters')</button>
                <button type="button" class="btn btn-light" wire:click="clearFilters">@lang('driver::home.clear_filters')</button>
            </div>
        </form>
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
                            <td>STL-{{ $settlement->batch_number }}</td>
                            <td>{{ collect($settlement->batch_orders)->map(fn ($code) => '#' . $code)->implode(', ') }}</td>
                            <td>{{ number_format($settlement->batch_amount, 2) }} SAR</td>
                            <td>@lang('driver::home.supply_method_' . $settlement->method)</td>
                            <td>
                                {{ ($lastSubmission?->created_at ?? $settlement->created_at)?->format('Y-m-d H:i') ?? '—' }}
                                @if($settlement->supply_date)
                                    <small class="text-muted d-block">@lang('driver::home.supply_date'): {{ $settlement->supply_date->toDateString() }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge {{ $settlement->status === 'approved' ? 'bg-success' : ($settlement->status === 'rejected' ? 'bg-danger' : ($settlement->status === 'returned' ? 'bg-warning' : 'bg-info')) }}">
                                    @lang('driver::home.settlement_status_' . $settlement->status)
                                </span>
                            </td>
                            <td>
                                @if($settlement->batch_latest_history)
                                    @lang('driver::home.settlement_action_' . $settlement->batch_latest_history->action)
                                    <small class="text-muted d-block">{{ $settlement->batch_latest_history->created_at?->format('Y-m-d H:i') }}</small>
                                    @if($settlement->batch_latest_history->note)
                                        <span class="text-danger d-block">{{ $settlement->batch_latest_history->note }}</span>
                                    @endif
                                @else
                                    —
                                @endif
                            </td>
                            <td>
                                <button type="button" class="btn btn-sm btn-outline-primary"
                                        onclick="Livewire.emit('viewDriverSettlementBatch', {{ $settlement->batch_number }})">
                                    @lang('driver::home.settlement_batch_details')
                                </button>
                                @if($settlement->status === 'returned')
                                    <button type="button" class="btn btn-sm btn-warning"
                                            onclick="Livewire.emit('resubmitDriverSettlementBatch', {{ $settlement->batch_number }})">
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
    <livewire:driver::settlement.create />
</div>
