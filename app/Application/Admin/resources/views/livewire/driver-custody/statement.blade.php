<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h4 class="mb-1">{{ __('admin::home.driver_custody_statement') }}: {{ $driver->name }}</h4>
            <span class="text-muted">{{ $driver->phone }}</span>
        </div>
        @if(auth('admin')->check())
            <a href="{{ route('admin:driver-custody.index') }}" class="btn btn-outline-secondary">
                @lang('admin::home.custody_back')
            </a>
        @endif
    </div>

    <form class="card custom-card mb-3" wire:submit.prevent="applyFilters">
        <div class="card-body">
            <div class="row g-2 align-items-end">
                <div class="col-md-2">
                    <label class="form-label">@lang('admin::home.custody_from_date')</label>
                    <input type="date" class="form-control" wire:model.defer="from">
                    @error('from') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('admin::home.custody_to_date')</label>
                    <input type="date" class="form-control" wire:model.defer="to">
                    @error('to') <span class="text-danger small">{{ $message }}</span> @enderror
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('admin::home.custody_movement_type')</label>
                    <select class="form-select" wire:model.defer="movementType">
                        <option value="">@lang('admin::home.custody_all_types')</option>
                        <option value="collection">@lang('admin::home.custody_collection')</option>
                        <option value="settlement">@lang('admin::home.custody_approved_settlement')</option>
                        <option value="correction">@lang('admin::home.custody_correction')</option>
                        <option value="refund">@lang('admin::home.custody_refund')</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('admin::home.custody_order_search')</label>
                    <input class="form-control" wire:model.defer="orderSearch" placeholder="#...">
                </div>
                <div class="col-md-2">
                    <label class="form-label">@lang('admin::home.custody_settlement_search')</label>
                    <input class="form-control" wire:model.defer="settlementSearch" placeholder="STL-...">
                </div>
                <div class="col-md-2 d-flex gap-2">
                    <button class="btn btn-primary" type="submit">@lang('admin::home.custody_search')</button>
                    <button class="btn btn-light" type="button" wire:click="resetFilters">@lang('admin::home.custody_reset')</button>
                </div>
            </div>
        </div>
    </form>

    <div class="row g-3 mb-3">
        <div class="col-md-6">
            <div class="card custom-card mb-0">
                <div class="card-body">
                    <span class="text-muted">@lang('admin::home.custody_opening_balance')</span>
                    <h4 class="mb-0">{{ number_format($statement['opening_balance'], 2) }} SAR</h4>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card custom-card mb-0">
                <div class="card-body">
                    <span class="text-muted">@lang('admin::home.custody_closing_balance')</span>
                    <h4 class="mb-0">{{ number_format($statement['closing_balance'], 2) }} SAR</h4>
                </div>
            </div>
        </div>
    </div>

    @if($canCorrect)
        <div class="alert alert-info">
            @lang('admin::home.custody_correction_from_movement')
        </div>
    @endif

    <div class="card custom-card">
        <div class="card-header"><h6 class="mb-0">@lang('admin::home.custody_movements')</h6></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-bordered align-middle">
                    <thead>
                        <tr>
                            <th>@lang('admin::home.custody_movement_number')</th>
                            <th>@lang('admin::home.custody_date')</th>
                            <th>@lang('admin::home.custody_movement_type')</th>
                            <th>@lang('admin::home.custody_amount')</th>
                            <th>@lang('admin::home.custody_order')</th>
                            <th>@lang('admin::home.custody_settlement')</th>
                            <th>@lang('admin::home.custody_settlement_status')</th>
                            <th>@lang('admin::home.custody_details')</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($statement['movements'] as $movement)
                            <tr>
                                <td>
                                    @if(auth('admin')->check())
                                        @if($movement->source_movement_type === 'settlement')
                                            <a href="{{ route('admin:driver-settlements.show', $movement->source_movement_id) }}">
                                                {{ $movement->movement_number ?: $movement->record_id }}
                                            </a>
                                        @else
                                            <a href="{{ route('admin:merchant.orders.showOrder', $movement->order_id) }}">
                                                {{ $movement->movement_number ?: $movement->record_id }}
                                            </a>
                                        @endif
                                    @else
                                        <button type="button" class="btn btn-link p-0"
                                                onclick="Livewire.emit('showOrderDetails', {{ $movement->order_id }})">
                                            {{ $movement->movement_number ?: $movement->record_id }}
                                        </button>
                                    @endif
                                </td>
                                <td>{{ \Carbon\Carbon::parse($movement->occurred_at)->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($movement->movement_type === 'settlement')
                                        @lang('admin::home.custody_approved_settlement')
                                    @else
                                        @lang('admin::home.custody_' . $movement->movement_type)
                                    @endif
                                </td>
                                <td class="{{ (float) $movement->amount < 0 ? 'text-danger' : 'text-success' }}">
                                    {{ number_format($movement->amount, 2) }} SAR
                                </td>
                                <td>
                                    @if(auth('admin')->check())
                                        <a href="{{ route('admin:merchant.orders.showOrder', $movement->order_id) }}">
                                            #{{ $movement->order_code }}
                                        </a>
                                    @else
                                        #{{ $movement->order_code }}
                                    @endif
                                </td>
                                <td>
                                    @if(auth('admin')->check() && $movement->settlement_id)
                                        <a href="{{ route('admin:driver-settlements.show', $movement->settlement_id) }}">
                                            STL-{{ $movement->settlement_id }}
                                        </a>
                                    @else
                                        {{ $movement->settlement_id ? 'STL-' . $movement->settlement_id : '—' }}
                                    @endif
                                </td>
                                <td>
                                    {{ $movement->settlement_status
                                        ? __('admin::home.settlement_' . $movement->settlement_status)
                                        : '—' }}
                                </td>
                                <td>{{ $movement->details ?: '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="8" class="text-center text-muted">@lang('admin::home.no_custody_movements')</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            {{ $statement['movements']->links() }}
        </div>
    </div>
</div>
