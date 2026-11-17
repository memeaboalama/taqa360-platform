<div>
    <form wire:submit.prevent="applyFilters" class="row g-2 align-items-end mb-4">
        <div class="col-md-3">
            <label class="form-label">{{ __('marketplace::home.orderCode') }}</label>
            <input type="text" class="form-control" wire:model.defer="code" placeholder="{{ __('marketplace::home.enter order code') }}">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('marketplace::home.from_date') }}</label>
            <input type="date" class="form-control" wire:model.defer="fromDate">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('marketplace::home.to_date') }}</label>
            <input type="date" class="form-control" wire:model.defer="toDate">
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('marketplace::home.collection_status') }}</label>
            <select class="form-select" wire:model.defer="collectionStatus">
                <option value="">{{ __('marketplace::home.all') }}</option>
                <option value="collected">{{ __('marketplace::home.collection_recorded') }}</option>
                <option value="not_collected">{{ __('marketplace::home.collection_not_recorded') }}</option>
            </select>
        </div>
        <div class="col-md-2">
            <label class="form-label">{{ __('marketplace::home.settlement_status') }}</label>
            <select class="form-select" wire:model.defer="settlementStatus">
                <option value="">{{ __('marketplace::home.all') }}</option>
                <option value="not_submitted">{{ __('marketplace::home.not_remitted') }}</option>
                <option value="pending_review">{{ __('marketplace::home.remittance_under_review') }}</option>
                <option value="approved">{{ __('marketplace::home.remitted_and_approved') }}</option>
            </select>
        </div>
        <div class="col-md-1 d-flex gap-1">
            <button type="submit" class="btn btn-primary">{{ __('marketplace::home.filter') }}</button>
        </div>
        <div class="col-12">
            <button type="button" class="btn btn-sm btn-outline-secondary" wire:click="clearFilters">
                {{ __('marketplace::home.clear_filters') }}
            </button>
        </div>
    </form>

    <div class="row g-3 mb-4">
        @foreach([
            ['label' => __('marketplace::home.report_order_total'), 'value' => $totals['gross']],
            ['label' => __('marketplace::home.report_collected'), 'value' => $totals['collected']],
            ['label' => __('marketplace::home.report_refunded'), 'value' => $totals['refunded']],
            ['label' => __('marketplace::home.report_net_collected'), 'value' => $totals['net_collected']],
        ] as $total)
            <div class="col-sm-6 col-xl-3">
                <div class="card custom-card h-100">
                    <div class="card-body">
                        <div class="text-muted mb-1">{{ $total['label'] }}</div>
                        <strong>{{ number_format($total['value'], 2) }} {{ __('admin::home.SARR') }}</strong>
                    </div>
                </div>
            </div>
        @endforeach
        <div class="col-sm-6 col-xl-3">
            <div class="card custom-card h-100">
                <div class="card-body">
                    <div class="text-muted mb-1">{{ __('marketplace::home.report_merchant_due') }}</div>
                    <strong>
                        @if($totals['merchant_due_defined'])
                            {{ number_format($totals['merchant_due'], 2) }} {{ __('admin::home.SARR') }}
                        @else
                            {{ __('marketplace::home.merchant_due_undefined') }}
                        @endif
                    </strong>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card custom-card h-100">
                <div class="card-body">
                    <div class="text-muted mb-1">{{ __('marketplace::home.payout_status') }}</div>
                    <strong>{{ __('marketplace::home.payout_not_recorded') }}</strong>
                </div>
            </div>
        </div>
    </div>

    <div class="table-responsive">
        <table class="table table-bordered table-striped text-nowrap align-middle">
            <thead>
                <tr>
                    <th>{{ __('marketplace::home.orderCode') }}</th>
                    <th>{{ __('marketplace::home.report_order_total') }}</th>
                    <th>{{ __('marketplace::home.report_collected') }}</th>
                    <th>{{ __('marketplace::home.report_refunded') }}</th>
                    <th>{{ __('marketplace::home.report_net_collected') }}</th>
                    <th>{{ __('marketplace::home.collection_status') }}</th>
                    <th>{{ __('marketplace::home.settlement_status') }}</th>
                    <th>{{ __('marketplace::home.settlement_number') }}</th>
                    <th>{{ __('marketplace::home.report_merchant_due') }}</th>
                    <th>{{ __('marketplace::home.approved_deductions') }}</th>
                    <th>{{ __('marketplace::home.payout_status') }}</th>
                    <th>{{ __('marketplace::home.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $row)
                    @php($order = $row['order'])
                    <tr>
                        <td>{{ $order->code }}</td>
                        <td>{{ number_format($row['gross'], 2) }}</td>
                        <td>{{ number_format($row['collected'], 2) }}</td>
                        <td>{{ number_format($row['refunded'], 2) }}</td>
                        <td>{{ number_format($row['net_collected'], 2) }}</td>
                        <td>{{ $row['collection_status'] }}</td>
                        <td>
                            @if($row['settlement_status'] === 'approved')
                                {{ __('marketplace::home.remitted_and_approved') }}
                            @elseif($row['settlement_status'] === 'pending_review')
                                {{ __('marketplace::home.remittance_under_review') }}
                            @else
                                {{ __('marketplace::home.not_remitted') }}
                            @endif
                        </td>
                        <td>{{ $row['settlement_number'] ?: '—' }}</td>
                        <td>
                            @if($row['merchant_due'] === null)
                                {{ __('marketplace::home.merchant_due_undefined') }}
                            @else
                                {{ number_format($row['merchant_due'], 2) }}
                            @endif
                        </td>
                        <td>
                            @if($row['deductions'] === null)
                                {{ __('marketplace::home.merchant_due_undefined') }}
                            @else
                                {{ __('marketplace::home.commission') }}: {{ number_format($row['deductions']['commission'], 2) }},
                                {{ __('marketplace::home.discount') }}: {{ number_format($row['deductions']['discount'], 2) }},
                                {{ __('marketplace::home.payment_fee') }}: {{ number_format($row['deductions']['payment_fee'], 2) }},
                                {{ __('marketplace::home.report_refunded') }}: {{ number_format($row['deductions']['refund'], 2) }}
                            @endif
                        </td>
                        <td>{{ $row['payout_status'] }}</td>
                        <td>
                            <a class="btn btn-sm btn-info-transparent rounded-pill"
                               href="{{ route('marketplace:showOrder', $order->id) }}">
                                {{ __('marketplace::home.show') }}
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="12" class="text-center text-muted py-4">
                            {{ __('marketplace::home.no_records') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $orders->links() }}
</div>
