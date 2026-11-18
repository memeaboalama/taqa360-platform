<div>
    <h4 class="mb-3">@lang('driver::home.my_custody')</h4>

    <form class="card custom-card mb-3" wire:submit.prevent="applyFilters">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">@lang('driver::home.custody_from_date')</label>
                    <input type="date" class="form-control" wire:model.defer="from">
                    @error('from') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('driver::home.custody_to_date')</label>
                    <input type="date" class="form-control" wire:model.defer="to">
                    @error('to') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('driver::home.custody_order_search')</label>
                    <input class="form-control" wire:model.defer="orderSearch" placeholder="#...">
                </div>
                <div class="col-md-3">
                    <label class="form-label">@lang('driver::home.custody_settlement_status')</label>
                    <select class="form-select" wire:model.defer="settlementStatus">
                        <option value="">@lang('driver::home.custody_all_statuses')</option>
                        @foreach(['not_submitted', 'pending_review', 'reviewed', 'approved', 'returned', 'rejected'] as $status)
                            <option value="{{ $status }}">@lang('driver::home.settlement_status_' . $status)</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-12 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">@lang('driver::home.custody_search')</button>
                    <button class="btn btn-light" type="button" wire:click="resetFilters">@lang('driver::home.custody_reset')</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-4">
            <div class="card custom-card mb-0 h-100">
                <div class="card-body">
                    <span class="text-muted">@lang('driver::home.custody_balance')</span>
                    <h4 class="mb-0">{{ number_format($statement['custody_balance'], 2) }} SAR</h4>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card mb-0 h-100">
                <div class="card-body">
                    <span class="text-muted">@lang('driver::home.custody_pending_review')</span>
                    <h4 class="mb-1">{{ number_format($statement['pending_settlement_amount'], 2) }} SAR</h4>
                    <small class="text-muted">@lang('driver::home.custody_pending_is_in_balance')</small>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card custom-card mb-0 h-100">
                <div class="card-body">
                    <span class="text-muted">@lang('driver::home.custody_approved_period')</span>
                    <h4 class="mb-0">{{ number_format($statement['approved_settlement_amount'], 2) }} SAR</h4>
                </div>
            </div>
        </div>
    </div>

    <div class="card custom-card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2">
                <h5 class="card-title mb-0">@lang('driver::home.custody_movements')</h5>
                <button type="button" class="btn btn-primary"
                        onclick="Livewire.emit('openDriverSettlementForm')">
                    @lang('driver::home.create_settlement_request')
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>@lang('driver::home.settlement_order')</th>
                            <th>@lang('driver::home.custody_movement_number')</th>
                            <th>@lang('driver::home.custody_movement_type')</th>
                            <th>@lang('driver::home.custody_date')</th>
                            <th>@lang('driver::home.custody_amount')</th>
                            <th>@lang('driver::home.custody_settlement_status')</th>
                            <th>@lang('driver::home.custody_settlement_number')</th>
                            <th>@lang('driver::home.actions')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statement['movements'] as $movement)
                            <tr>
                                <td>#{{ $movement->order_code }}</td>
                                <td>{{ $movement->movement_number ?: $movement->record_id }}</td>
                                <td>@lang('driver::home.custody_type_' . $movement->movement_type)</td>
                                <td>{{ \Carbon\Carbon::parse($movement->occurred_at)->format('Y-m-d H:i') }}</td>
                                <td class="{{ (float) $movement->amount < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($movement->amount, 2) }} SAR
                                </td>
                                <td>@lang('driver::home.settlement_status_' . ($movement->settlement_status ?: 'not_submitted'))</td>
                                <td>{{ $movement->settlement_id ? 'STL-' . $movement->settlement_id : '—' }}</td>
                                <td class="text-nowrap">
                                    <button type="button" class="btn btn-sm btn-outline-primary"
                                            wire:click="viewMovement('{{ $movement->movement_type }}', {{ $movement->record_id }})">
                                        @lang('driver::home.custody_view_movement')
                                    </button>
                                    @if($movement->movement_type === 'collection'
                                        && in_array($movement->settlement_status, [null, 'returned', 'rejected'], true))
                                        <button type="button" class="btn btn-sm btn-primary"
                                                onclick="Livewire.emit('openDriverSettlementForm', {{ $movement->record_id }})">
                                            @lang('driver::home.custody_create_settlement')
                                        </button>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="text-center text-muted">@lang('driver::home.custody_no_movements')</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $statement['movements']->links() }}
        </div>
    </div>

    <div class="modal fade" id="driverCustodyMovementModal" wire:ignore.self tabindex="-1"
         aria-labelledby="driverCustodyMovementModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="driverCustodyMovementModalLabel">@lang('driver::home.custody_movement_details')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <dl class="row mb-0">
                        <dt class="col-5">@lang('driver::home.settlement_order')</dt>
                        <dd class="col-7">#{{ $movementDetails['order_code'] ?? '—' }}</dd>
                        <dt class="col-5">@lang('driver::home.custody_movement_number')</dt>
                        <dd class="col-7">{{ $movementDetails['movement_number'] ?? '—' }}</dd>
                        <dt class="col-5">@lang('driver::home.custody_date')</dt>
                        <dd class="col-7">
                            {{ !empty($movementDetails['occurred_at']) ? \Carbon\Carbon::parse($movementDetails['occurred_at'])->format('Y-m-d H:i') : '—' }}
                        </dd>
                        <dt class="col-5">@lang('driver::home.custody_amount')</dt>
                        <dd class="col-7">
                            {{ isset($movementDetails['amount']) ? number_format($movementDetails['amount'], 2) . ' SAR' : '—' }}
                        </dd>
                        <dt class="col-5">@lang('driver::home.custody_movement_type')</dt>
                        <dd class="col-7">
                            @if(!empty($movementDetails['movement_type']))
                                @lang('driver::home.custody_type_' . $movementDetails['movement_type'])
                            @else
                                —
                            @endif
                        </dd>
                        <dt class="col-5">@lang('driver::home.custody_settlement_status')</dt>
                        <dd class="col-7">
                            @lang('driver::home.settlement_status_' . ($movementDetails['settlement_status'] ?? 'not_submitted'))
                        </dd>
                        <dt class="col-5">@lang('driver::home.custody_settlement_number')</dt>
                        <dd class="col-7">
                            {{ !empty($movementDetails['settlement_id']) ? 'STL-' . $movementDetails['settlement_id'] : '—' }}
                        </dd>
                        <dt class="col-5">@lang('driver::home.custody_details')</dt>
                        <dd class="col-7">{{ $movementDetails['details'] ?? '—' }}</dd>
                    </dl>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">@lang('driver::home.cancel')</button>
                </div>
            </div>
        </div>
    </div>

    <livewire:driver::home.order.modal.show />
    <livewire:driver::settlement.create />
</div>
