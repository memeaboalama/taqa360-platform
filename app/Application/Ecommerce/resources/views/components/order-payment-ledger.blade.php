<div class="card custom-card mb-4">
    <div class="card-header">
        <h5 class="card-title mb-0">@lang('ecommerce::ecommerce.payment_collection')</h5>
    </div>
    <div class="card-body">

    @php
        $paymentStatusClass = match($paymentLedger['payment_status']) {
            'paid' => 'bg-success',
            'pending', 'initiated' => 'bg-warning',
            'failed' => 'bg-danger',
            default => 'bg-secondary',
        };
    @endphp

    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-lg-4">
            <div class="border rounded p-3 h-100">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.payment_method')</small>
                <strong>{{ $paymentLedger['payment_method'] }}</strong>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="border rounded p-3 h-100">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.payment_status')</small>
                <span class="badge {{ $paymentStatusClass }}">{{ $paymentLedger['payment_label'] }}</span>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="border rounded p-3 h-100">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.amount_due')</small>
                <strong>{{ number_format($paymentLedger['due'], 2) }} SAR</strong>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="border rounded p-3 h-100">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.amount_collected')</small>
                <strong>{{ number_format($paymentLedger['collected'], 2) }} SAR</strong>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="border rounded p-3 h-100">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.amount_remaining')</small>
                <strong>{{ number_format($paymentLedger['remaining'], 2) }} SAR</strong>
            </div>
        </div>
        <div class="col-sm-6 col-lg-4">
            <div class="border rounded p-3 h-100">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.amount_refunded')</small>
                <strong>{{ number_format($paymentLedger['refunded'], 2) }} SAR</strong>
            </div>
        </div>
        <div class="col-12">
            <div class="border rounded p-3">
                <small class="text-muted d-block">@lang('ecommerce::ecommerce.money_location')</small>
                <strong>{{ $paymentLedger['custody'] }}</strong>
            </div>
        </div>
    </div>

    <h6 class="fw-semibold">@lang('ecommerce::ecommerce.payment_movements')</h6>
    <div class="table-responsive">
        <table class="table table-bordered align-middle mb-0">
            <thead>
                <tr>
                    <th>@lang('ecommerce::ecommerce.movement_number')</th>
                    <th>@lang('ecommerce::ecommerce.movement_amount')</th>
                    <th>@lang('ecommerce::ecommerce.payment_method')</th>
                    <th>@lang('ecommerce::ecommerce.collector')</th>
                    <th>@lang('ecommerce::ecommerce.movement_time')</th>
                    <th>@lang('ecommerce::ecommerce.settlement_number')</th>
                    <th>@lang('ecommerce::ecommerce.settlement_status')</th>
                    <th>@lang('ecommerce::ecommerce.movement_actions')</th>
                </tr>
            </thead>
            <tbody>
                @forelse($paymentLedger['movements'] as $movement)
                    <tr>
                        <td>{{ $movement['number'] ?: '—' }}</td>
                        <td class="{{ $movement['type'] === 'refund' ? 'text-danger' : 'text-success' }}">
                            {{ number_format($movement['amount'], 2) }} SAR
                        </td>
                        <td>{{ $movement['method'] }}</td>
                        <td>{{ $movement['collector'] }}</td>
                        <td>{{ $movement['occurred_at'] }}</td>
                        <td>{{ $movement['settlement_number'] ?: '—' }}</td>
                        <td>
                            @if($movement['settlement_status'])
                                @lang('ecommerce::ecommerce.settlement_' . $movement['settlement_status'])
                            @else
                                —
                            @endif
                        </td>
                        <td class="text-nowrap">
                            <details>
                                <summary class="text-primary" style="cursor:pointer">@lang('ecommerce::ecommerce.view_movement')</summary>
                                <div class="small mt-2 text-wrap">
                                    <div>@lang('ecommerce::ecommerce.movement_type'): {{ $movement['type'] === 'refund' ? __('ecommerce::ecommerce.refund') : __('ecommerce::ecommerce.collection') }}</div>
                                    @if($movement['details'])
                                        <div>@lang('ecommerce::ecommerce.movement_details'): {{ $movement['details'] }}</div>
                                    @endif
                                </div>
                            </details>
                            @if(!empty($allowCorrection))
                                <button type="button" class="btn btn-link btn-sm p-0 mt-2"
                                        wire:click="beginCorrectionRequest('{{ $movement['type'] }}', {{ $movement['record_id'] }})">
                                    @lang('ecommerce::ecommerce.request_correction')
                                </button>
                                @if(($correctionMovementType ?? null) === $movement['type']
                                    && (int) ($correctionMovementId ?? 0) === (int) $movement['record_id'])
                                    <form class="mt-2" wire:submit.prevent="submitCorrectionRequest">
                                        <textarea class="form-control form-control-sm mb-2"
                                                  wire:model.defer="correctionReason"
                                                  maxlength="1000"
                                                  placeholder="@lang('ecommerce::ecommerce.correction_reason')"
                                                  required></textarea>
                                        @error('correctionReason') <span class="text-danger small">{{ $message }}</span> @enderror
                                        <button type="submit" class="btn btn-warning btn-sm" wire:loading.attr="disabled"
                                                wire:target="submitCorrectionRequest">
                                            @lang('ecommerce::ecommerce.submit_correction_request')
                                        </button>
                                    </form>
                                @endif
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted">@lang('ecommerce::ecommerce.no_payment_movements')</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if(!empty($allowCorrection) && isset($correctionRequests) && $correctionRequests->isNotEmpty())
        <h6 class="fw-semibold mt-4">@lang('ecommerce::ecommerce.correction_requests')</h6>
        <div class="table-responsive">
            <table class="table table-sm table-bordered mb-0">
                <thead>
                    <tr>
                        <th>@lang('ecommerce::ecommerce.movement_number')</th>
                        <th>@lang('ecommerce::ecommerce.correction_reason')</th>
                        <th>@lang('ecommerce::ecommerce.requested_by')</th>
                        <th>@lang('ecommerce::ecommerce.request_status')</th>
                        <th>@lang('ecommerce::ecommerce.movement_time')</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($correctionRequests as $request)
                        <tr>
                            <td>{{ strtoupper($request->movement_type) }}-{{ $request->movement_id }}</td>
                            <td>{{ $request->reason }}</td>
                            <td>{{ $request->requestedBy?->name ?? '—' }}</td>
                            <td>@lang('ecommerce::ecommerce.correction_request_' . $request->status)</td>
                            <td>{{ $request->created_at?->format('Y-m-d H:i') ?? '—' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
