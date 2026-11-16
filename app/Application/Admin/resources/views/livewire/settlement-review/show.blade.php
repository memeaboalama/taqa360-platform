<div>
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h4>@lang('admin::home.settlement_review') STL-{{ $settlement->id }}</h4>
        <a href="{{ route('admin:driver-settlements.index') }}" class="btn btn-outline-secondary">@lang('admin::home.back_to_settlements')</a>
    </div>

    <div class="row g-3">
        <div class="col-lg-7">
            <div class="card custom-card">
                <div class="card-header"><h6 class="mb-0">@lang('admin::home.settlement_details')</h6></div>
                <div class="card-body">
                    <dl class="row mb-0">
                        <dt class="col-sm-4">@lang('admin::home.order')</dt>
                        <dd class="col-sm-8">
                            <a href="{{ route('admin:merchant.orders.showOrder', $settlement->order_id) }}">
                                #{{ $settlement->order?->code }}
                            </a>
                        </dd>
                        <dt class="col-sm-4">@lang('admin::home.driver_name')</dt>
                        <dd class="col-sm-8">{{ $settlement->driver?->name ?? '—' }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.recipient')</dt>
                        <dd class="col-sm-8">{{ __('admin::home.' . ($settlement->recipient === 'merchant' ? 'merchant_recipient' : 'platform_recipient')) }} — {{ $settlement->recipient_name }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.total_amount')</dt>
                        <dd class="col-sm-8 fw-bold">{{ number_format($settlement->amount, 2) }} SAR</dd>
                        <dt class="col-sm-4">@lang('admin::home.order_total')</dt>
                        <dd class="col-sm-8">{{ number_format($settlement->order?->total_after_vat ?? 0, 2) }} SAR</dd>
                        <dt class="col-sm-4">@lang('admin::home.collected_amount')</dt>
                        <dd class="col-sm-8">{{ number_format($collectedAmount, 2) }} SAR</dd>
                        <dt class="col-sm-4">@lang('admin::home.payment_number')</dt>
                        <dd class="col-sm-8">{{ $collections->pluck('payment_id')->filter()->implode(', ') ?: '—' }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.supply_method')</dt>
                        <dd class="col-sm-8">@lang('admin::home.supply_method_' . $settlement->method)</dd>
                        <dt class="col-sm-4">@lang('admin::home.reference')</dt>
                        <dd class="col-sm-8">{{ $settlement->reference ?: '—' }}</dd>
                        <dt class="col-sm-4">@lang('admin::home.settlement_status')</dt>
                        <dd class="col-sm-8">@lang('admin::home.settlement_status_' . $settlement->status)</dd>
                        @if($settlement->received_amount !== null)
                            <dt class="col-sm-4">@lang('admin::home.actual_received_amount')</dt>
                            <dd class="col-sm-8">{{ number_format($settlement->received_amount, 2) }} SAR</dd>
                            <dt class="col-sm-4">@lang('admin::home.received_at')</dt>
                            <dd class="col-sm-8">{{ $settlement->received_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                            <dt class="col-sm-4">@lang('admin::home.received_by')</dt>
                            <dd class="col-sm-8">{{ $settlement->received_by ?: '—' }}</dd>
                        @endif
                        <dt class="col-sm-4">@lang('admin::home.instructions')</dt>
                        <dd class="col-sm-8">{{ $settlement->instructions }}</dd>
                        @if($settlement->account_data)
                            <dt class="col-sm-4">@lang('admin::home.account_details')</dt>
                            <dd class="col-sm-8">{{ $settlement->account_data }}</dd>
                        @endif
                        <dt class="col-sm-4">@lang('admin::home.proof')</dt>
                        <dd class="col-sm-8">
                            @if($settlement->proof_path)
                                <a href="{{ route('admin:settlements.proof', $settlement) }}" target="_blank" rel="noopener">
                                    @lang('admin::home.view_proof')
                                </a>
                            @else
                                —
                            @endif
                        </dd>
                    </dl>
                    <h6 class="mt-4">@lang('admin::home.settlement_collections')</h6>
                    <div class="table-responsive">
                        <table class="table table-sm table-bordered align-middle mb-0">
                            <thead>
                                <tr>
                                    <th>@lang('admin::home.payment_number')</th>
                                    <th>@lang('admin::home.collected_amount')</th>
                                    <th>@lang('admin::home.driver_name')</th>
                                    <th>@lang('admin::home.received_at')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($collections as $collection)
                                    <tr>
                                        <td>{{ $collection->payment_id }}</td>
                                        <td>{{ number_format((float) ($collection->captured ?: $collection->amount) / 100, 2) }} SAR</td>
                                        <td>{{ $collection->source_name ?: '—' }}</td>
                                        <td>{{ $collection->capturedAt ?: $collection->created_at?->format('Y-m-d H:i') ?: '—' }}</td>
                                    </tr>
                                @empty
                                    <tr><td colspan="4" class="text-center text-muted">@lang('admin::home.no_settlement_collections')</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
                @if($settlement->status === 'pending_review' && $canReview)
                    <div class="card-footer d-flex flex-wrap gap-2">
                        <button class="btn btn-outline-primary" wire:click="markReviewed" wire:loading.attr="disabled">
                            @lang('admin::home.confirm_review')
                        </button>
                        <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#returnSettlementForm">
                            @lang('admin::home.return_for_completion')
                        </button>
                        <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#rejectSettlementForm">
                            @lang('admin::home.reject_settlement')
                        </button>
                        @if($canApprove)
                            <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#approveSettlementForm">
                                @lang('admin::home.approve_settlement')
                            </button>
                        @endif
                    </div>
                @elseif($settlement->status === 'reviewed')
                    <div class="card-footer d-flex flex-wrap gap-2">
                        @if($canApprove)
                            <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#approveSettlementForm">
                                @lang('admin::home.approve_settlement')
                            </button>
                        @endif
                        @if($canReview)
                            <button class="btn btn-warning" type="button" data-bs-toggle="collapse" data-bs-target="#returnSettlementForm">
                                @lang('admin::home.return_for_completion')
                            </button>
                            <button class="btn btn-outline-danger" type="button" data-bs-toggle="collapse" data-bs-target="#rejectSettlementForm">
                                @lang('admin::home.reject_settlement')
                            </button>
                        @endif
                    </div>
                @elseif(in_array($settlement->status, ['pending_review', 'reviewed'], true) && $canApprove)
                    <div class="card-footer">
                        <button class="btn btn-success" type="button" data-bs-toggle="collapse" data-bs-target="#approveSettlementForm">
                            @lang('admin::home.approve_settlement')
                        </button>
                    </div>
                @endif
            </div>
            @if($canCorrect && $settlement->status === 'approved')
                <div class="card custom-card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">@lang('admin::home.financial_correction')</h6>
                        @if(!$showCorrectionForm)
                            <button type="button" class="btn btn-warning btn-sm" wire:click="beginCorrectionRequest">
                                @lang('admin::home.request_correction')
                            </button>
                        @endif
                    </div>
                    @if($showCorrectionForm)
                        <div class="card-body">
                            <div class="alert alert-light border">
                                <div><strong>@lang('admin::home.original_movement'):</strong> STL-{{ $settlement->id }}</div>
                                <div><strong>@lang('admin::home.original_amount'):</strong> {{ number_format($correctionPreview['original_amount'] ?? $settlement->amount, 2) }} SAR</div>
                            </div>
                            <form wire:submit.prevent="submitCorrectionRequest">
                                <label class="form-label">@lang('admin::home.corrected_amount')</label>
                                <input type="number" min="0" step="0.01" class="form-control mb-2"
                                       wire:model="correctionAmount" required>
                                @error('correctionAmount') <span class="text-danger">{{ $message }}</span> @enderror
                                @if($correctionPreview)
                                    <div class="row g-2 mb-3">
                                        <div class="col-md-6">
                                            <div class="border rounded p-2">
                                                @lang('admin::home.balance_before_correction'):
                                                <strong>{{ number_format($correctionPreview['balance_before'], 2) }} SAR</strong>
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="border rounded p-2">
                                                @lang('admin::home.balance_after_correction'):
                                                <strong>{{ number_format($correctionPreview['balance_after'], 2) }} SAR</strong>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <label class="form-label">@lang('admin::home.correction_reason')</label>
                                <textarea class="form-control mb-2" wire:model.defer="correctionReason" maxlength="1000" required></textarea>
                                @error('correctionReason') <span class="text-danger">{{ $message }}</span> @enderror
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled">
                                    @lang('admin::home.submit_correction_request')
                                </button>
                                <button type="button" class="btn btn-light" wire:click="$set('showCorrectionForm', false)">
                                    @lang('admin::home.cancel')
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            @endif
            @error('settlement') <div class="alert alert-warning mt-2">{{ $message }}</div> @enderror
            @error('correction') <div class="alert alert-warning mt-2">{{ $message }}</div> @enderror
            @if($canCorrect && $correctionRequests->isNotEmpty())
                <div class="card custom-card mt-3">
                    <div class="card-header"><h6 class="mb-0">@lang('admin::home.correction_history')</h6></div>
                    <div class="card-body">
                        @foreach($correctionRequests as $request)
                            <div class="border rounded p-3 mb-2">
                                <div class="d-flex justify-content-between flex-wrap gap-2">
                                    <strong>@lang('admin::home.correction_request_status_' . $request->status)</strong>
                                    <span>STL-{{ $request->movement_id }}</span>
                                </div>
                                <div class="small text-muted mt-1">
                                    @lang('admin::home.correction_requester'): {{ $request->requestedBy?->name ?? '—' }} ·
                                    {{ $request->created_at?->format('Y-m-d H:i') ?? '—' }}
                                </div>
                                <div>@lang('admin::home.original_amount'): {{ number_format($request->original_amount ?? 0, 2) }} SAR</div>
                                <div>@lang('admin::home.corrected_amount'): {{ number_format($request->corrected_amount ?? 0, 2) }} SAR</div>
                                <div>@lang('admin::home.correction_reason'): {{ $request->reason }}</div>
                                @if($request->status === 'approved')
                                    <div class="small text-muted">
                                        @lang('admin::home.correction_approver'): {{ $request->approvedBy?->name ?? '—' }} ·
                                        {{ $request->approved_at?->format('Y-m-d H:i') ?? '—' }}
                                    </div>
                                @elseif($request->status === 'cancelled')
                                    <div class="small text-muted">
                                        @lang('admin::home.correction_canceller'): {{ $request->cancelledBy?->name ?? '—' }} ·
                                        {{ $request->cancelled_at?->format('Y-m-d H:i') ?? '—' }}
                                    </div>
                                @elseif($request->status === 'pending')
                                    <div class="mt-2 d-flex gap-2">
                                        @if($request->corrected_amount !== null)
                                            <button type="button" class="btn btn-success btn-sm"
                                                    wire:click="approveCorrectionRequest({{ $request->id }})" wire:loading.attr="disabled">
                                                @lang('admin::home.approve_correction')
                                            </button>
                                        @endif
                                        <button type="button" class="btn btn-outline-danger btn-sm"
                                                wire:click="cancelCorrectionRequest({{ $request->id }})" wire:loading.attr="disabled">
                                            @lang('admin::home.cancel_correction')
                                        </button>
                                    </div>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
            @if($canApprove && in_array($settlement->status, ['pending_review', 'reviewed'], true))
                <div id="approveSettlementForm" class="collapse card custom-card mt-3">
                    <div class="card-body">
                        <form wire:submit.prevent="approve">
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="form-label">@lang('admin::home.actual_received_amount')</label>
                                    <input type="number" min="0.01" step="0.01" class="form-control" wire:model.defer="receivedAmount" required>
                                    @error('receivedAmount') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">@lang('admin::home.received_at')</label>
                                    <input type="datetime-local" class="form-control" wire:model.defer="receivedAt" required>
                                    @error('receivedAt') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">@lang('admin::home.received_by')</label>
                                    <input type="text" maxlength="255" class="form-control" wire:model.defer="receivedBy" required>
                                    @error('receivedBy') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12">
                                    <label class="form-check">
                                        <input type="checkbox" class="form-check-input" wire:model.defer="receiptConfirmed">
                                        <span class="form-check-label">@lang('admin::home.receipt_confirmation')</span>
                                    </label>
                                    @error('receiptConfirmed') <span class="text-danger">{{ $message }}</span> @enderror
                                </div>
                                <div class="col-12">
                                    <button class="btn btn-success" type="submit" wire:loading.attr="disabled">@lang('admin::home.approve_settlement')</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            @endif

            @if($canReview && in_array($settlement->status, ['pending_review', 'reviewed'], true))
                <div id="returnSettlementForm" class="collapse card custom-card mt-3">
                    <div class="card-body">
                        <form wire:submit.prevent="returnForCompletion">
                            <label class="form-label">@lang('admin::home.return_reason')</label>
                            <textarea class="form-control mb-2" wire:model.defer="reviewNote" required maxlength="1000"></textarea>
                            @error('reviewNote') <span class="text-danger">{{ $message }}</span> @enderror
                            <button class="btn btn-warning" type="submit" wire:loading.attr="disabled">@lang('admin::home.return_for_completion')</button>
                        </form>
                    </div>
                </div>
                <div id="rejectSettlementForm" class="collapse card custom-card mt-3">
                    <div class="card-body">
                        <form wire:submit.prevent="reject">
                            <label class="form-label">@lang('admin::home.rejection_reason')</label>
                            <textarea class="form-control mb-2" wire:model.defer="reviewNote" required maxlength="1000"></textarea>
                            @error('reviewNote') <span class="text-danger">{{ $message }}</span> @enderror
                            <button class="btn btn-danger" type="submit" wire:loading.attr="disabled">@lang('admin::home.reject_settlement')</button>
                        </form>
                    </div>
                </div>
            @endif
        </div>

        <div class="col-lg-5">
            <div class="card custom-card">
                <div class="card-header"><h6 class="mb-0">@lang('admin::home.settlement_action_history')</h6></div>
                <div class="card-body">
                    @forelse($settlement->histories as $history)
                        <div class="border-bottom pb-2 mb-2">
                            <div class="fw-semibold">
                                @lang('admin::home.settlement_action_' . $history->action)
                                <span class="badge bg-secondary">@lang('admin::home.settlement_status_' . $history->status_to)</span>
                            </div>
                            <small class="text-muted">
                                {{ $history->actor?->name ?? '—' }} · {{ $history->created_at?->format('Y-m-d H:i') }}
                            </small>
                            @if($history->note)
                                <p class="mb-0 mt-1">{{ $history->note }}</p>
                            @endif
                            @if(!empty($history->snapshot['proof_path']))
                                <a href="{{ route('admin:settlements.history.proof', [$settlement, $history]) }}">
                                    @lang('admin::home.view_proof')
                                </a>
                            @endif
                        </div>
                    @empty
                        <p class="text-muted mb-0">@lang('admin::home.no_settlement_history')</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</div>
