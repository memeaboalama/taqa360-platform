<div>
    <div class="modal fade" id="driverSettlementSubmissionModal" wire:ignore.self tabindex="-1"
         aria-labelledby="driverSettlementSubmissionModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="driverSettlementSubmissionModalLabel">
                        {{ $showBatchDetails ? __('driver::home.settlement_batch_details') : __('driver::home.create_settlement_request') }}
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @if($showBatchDetails)
                        <div class="alert alert-success">
                            <strong>{{ $batchDetails['number'] ?? '' }}</strong> —
                            @lang('driver::home.settlement_status_' . ($batchDetails['status'] ?? 'pending_review'))
                        </div>
                        <dl class="row">
                            <dt class="col-sm-4">@lang('driver::home.settlement_amount')</dt>
                            <dd class="col-sm-8">{{ number_format($batchDetails['total'] ?? 0, 2) }} SAR</dd>
                            @if(($batchDetails['status'] ?? null) === 'approved')
                                <dt class="col-sm-4">@lang('driver::home.approved_received_amount')</dt>
                                <dd class="col-sm-8">{{ number_format($batchDetails['received_amount'] ?? 0, 2) }} SAR</dd>
                                <dt class="col-sm-4">@lang('driver::home.approved_by')</dt>
                                <dd class="col-sm-8">{{ $batchDetails['approved_by'] ?? '—' }}</dd>
                                <dt class="col-sm-4">@lang('driver::home.approved_at')</dt>
                                <dd class="col-sm-8">{{ $batchDetails['approved_at'] ?? '—' }}</dd>
                            @endif
                            <dt class="col-sm-4">@lang('driver::home.settlement_recipient')</dt>
                            <dd class="col-sm-8">
                                @if(($batchDetails['recipient'] ?? null) === 'merchant')
                                    @lang('ecommerce::ecommerce.merchant')
                                @elseif(($batchDetails['recipient'] ?? null) === 'platform')
                                    @lang('ecommerce::ecommerce.platform')
                                @else
                                    —
                                @endif
                                @if(!empty($batchDetails['recipient_name']))
                                    — {{ $batchDetails['recipient_name'] }}
                                @endif
                            </dd>
                            <dt class="col-sm-4">@lang('driver::home.supply_method')</dt>
                            <dd class="col-sm-8">
                                @if(!empty($batchDetails['method']))
                                    @lang('driver::home.supply_method_' . $batchDetails['method'])
                                @else
                                    —
                                @endif
                            </dd>
                            <dt class="col-sm-4">@lang('driver::home.supply_date')</dt>
                            <dd class="col-sm-8">{{ $batchDetails['supply_date'] ?? '—' }}</dd>
                            <dt class="col-sm-4">@lang('driver::home.settlement_reference')</dt>
                            <dd class="col-sm-8">{{ $batchDetails['reference'] ?? '—' }}</dd>
                            @if(!empty($batchDetails['instructions']))
                                <dt class="col-sm-4">@lang('driver::home.supply_instructions')</dt>
                                <dd class="col-sm-8">{{ $batchDetails['instructions'] }}</dd>
                            @endif
                            @if(!empty($batchDetails['account_data']))
                                <dt class="col-sm-4">@lang('driver::home.approved_account')</dt>
                                <dd class="col-sm-8">{{ $batchDetails['account_data'] }}</dd>
                            @endif
                            @if(!empty($batchDetails['proof_url']))
                                <dt class="col-sm-4">@lang('driver::home.settlement_proof')</dt>
                                <dd class="col-sm-8"><a href="{{ $batchDetails['proof_url'] }}">@lang('driver::home.view_settlement_proof')</a></dd>
                            @endif
                        </dl>
                        @if(in_array($batchDetails['status'] ?? null, ['returned', 'rejected'], true) && !empty($batchDetails['latest_note']))
                            <div class="alert {{ ($batchDetails['status'] ?? null) === 'returned' ? 'alert-warning' : 'alert-danger' }}">
                                <strong>@lang('driver::home.admin_decision_note'):</strong> {{ $batchDetails['latest_note'] }}
                            </div>
                        @endif
                        <h6>@lang('driver::home.settlement_orders')</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('driver::home.settlement_order')</th>
                                        <th>@lang('driver::home.settlement_amount')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($batchDetails['orders'] ?? [] as $order)
                                        <tr>
                                            <td>#{{ $order['code'] }}</td>
                                            <td>{{ number_format($order['amount'], 2) }} SAR</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <h6 class="mt-4">@lang('driver::home.settlement_collections')</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered">
                                <thead>
                                    <tr>
                                        <th>@lang('driver::home.settlement_order')</th>
                                        <th>@lang('driver::home.custody_movement_number')</th>
                                        <th>@lang('driver::home.settlement_amount')</th>
                                        <th>@lang('driver::home.collection_date')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($batchDetails['collections'] ?? [] as $collection)
                                        <tr>
                                            <td>#{{ $collection['order_code'] ?? '—' }}</td>
                                            <td>{{ $collection['payment_number'] }}</td>
                                            <td>{{ number_format($collection['amount'], 2) }} SAR</td>
                                            <td>{{ $collection['occurred_at'] ?: '—' }}</td>
                                        </tr>
                                    @empty
                                        <tr><td colspan="4" class="text-center text-muted">@lang('driver::home.no_settlement_collections')</td></tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                        <h6 class="mt-4">@lang('driver::home.settlement_action_history')</h6>
                        @forelse($batchDetails['history'] ?? [] as $item)
                            <div class="border-bottom pb-2 mb-2">
                                <div class="fw-semibold">
                                    @lang('driver::home.settlement_action_' . $item['action'])
                                    <span class="badge bg-secondary">@lang('driver::home.settlement_status_' . $item['status'])</span>
                                </div>
                                <small class="text-muted">{{ $item['actor'] ?? '—' }} · {{ $item['created_at'] ?? '—' }}</small>
                                @if(!empty($item['note']))
                                    <p class="mb-1 mt-1">{{ $item['note'] }}</p>
                                @endif
                                @if(!empty($item['reference']))
                                    <div>@lang('driver::home.settlement_reference'): {{ $item['reference'] }}</div>
                                @endif
                                @if(!empty($item['proof_url']))
                                    <a href="{{ $item['proof_url'] }}">@lang('driver::home.view_settlement_proof')</a>
                                @endif
                            </div>
                        @empty
                            <p class="text-muted">@lang('driver::home.no_settlement_history')</p>
                        @endforelse
                    @else
                        @if($returnedBatchId)
                            <div class="alert alert-warning">
                                @lang('driver::home.complete_same_settlement', ['number' => 'STL-' . $returnedBatchId])
                            </div>
                        @else
                            <p class="text-muted">@lang('driver::home.settlement_select_collections')</p>
                        @endif

                        @if($settingsMissing)
                            <div class="alert alert-danger">@lang('driver::home.collection_settings_missing')</div>
                        @endif
                        @if($mixedRecipients)
                            <div class="alert alert-danger">@lang('driver::home.mixed_collection_recipients')</div>
                        @endif
                        @error('settings') <div class="alert alert-danger">{{ $message }}</div> @enderror
                        @error('selectedCollectionIds') <div class="alert alert-danger">{{ $message }}</div> @enderror

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle">
                                <thead>
                                    <tr>
                                        <th>@lang('driver::home.actions')</th>
                                        <th>@lang('driver::home.settlement_order')</th>
                                        <th>@lang('driver::home.custody_movement_number')</th>
                                        <th>@lang('driver::home.settlement_amount')</th>
                                        <th>@lang('driver::home.settlement_recipient')</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($collections as $collection)
                                        <tr wire:key="settlement-collection-{{ $collection['payment_id'] }}">
                                            <td>
                                                <input type="checkbox" class="form-check-input"
                                                       wire:model="selectedCollectionIds"
                                                       value="{{ $collection['payment_id'] }}"
                                                       @if($returnedBatchId && (int) $collection['batch_id'] !== $returnedBatchId) disabled @endif>
                                            </td>
                                            <td>#{{ $collection['order_code'] }}</td>
                                            <td>{{ $collection['payment_number'] ?: $collection['payment_id'] }}</td>
                                            <td>{{ number_format($collection['amount'], 2) }} SAR</td>
                                            <td>
                                                @if($collection['recipient'] === 'merchant')
                                                    @lang('ecommerce::ecommerce.merchant')
                                                @elseif($collection['recipient'] === 'platform')
                                                    @lang('ecommerce::ecommerce.platform')
                                                @else
                                                    —
                                                @endif
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">@lang('driver::home.no_eligible_collections')</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>

                        <div class="alert alert-light border">
                            @lang('driver::home.settlement_selected_count'): <strong>{{ $selectedCollectionIds ? count($selectedCollectionIds) : 0 }}</strong>
                            <span class="mx-2">|</span>
                            @lang('driver::home.settlement_selected_total'): <strong>{{ number_format($selectedTotal, 2) }} SAR</strong>
                        </div>

                        @if($selected->isEmpty())
                            <div class="alert alert-info">@lang('driver::home.select_collection_before_method')</div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('driver::home.cancel')</button>
                            </div>
                        @elseif($availableMethods)
                            <form wire:submit.prevent="submit">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('driver::home.supply_method')</label>
                                        <select class="form-select" wire:model="settlementMethod" required>
                                            <option value="">@lang('driver::home.choose_supply_method')</option>
                                            @foreach($availableMethods as $method)
                                                <option value="{{ $method }}">@lang('driver::home.supply_method_' . $method)</option>
                                            @endforeach
                                        </select>
                                        @error('settlementMethod') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">@lang('driver::home.supply_date')</label>
                                        <input type="date" class="form-control" wire:model.defer="supplyDate"
                                               max="{{ now()->toDateString() }}" required>
                                        @error('supplyDate') <span class="text-danger">{{ $message }}</span> @enderror
                                    </div>

                                    @if(in_array($settlementMethod, ['transfer', 'deposit'], true))
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('driver::home.settlement_reference') <span class="text-danger">*</span></label>
                                            <input class="form-control" wire:model.defer="settlementReference" maxlength="255" required>
                                            @error('settlementReference') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label">@lang('driver::home.settlement_proof') <span class="text-danger">*</span></label>
                                            <input class="form-control" type="file" wire:model="settlementProof"
                                                   accept=".jpg,.jpeg,.png,.pdf" required>
                                            @error('settlementProof') <span class="text-danger">{{ $message }}</span> @enderror
                                        </div>
                                    @endif
                                </div>

                                @php($methodData = $selected->first()['methods'][$settlementMethod] ?? [])
                                @if($methodData)
                                    <div class="alert alert-info mt-3">
                                        <div><strong>@lang('driver::home.settlement_recipient'):</strong> {{ $methodData['recipient_name'] ?? '' }}</div>
                                        <div>{{ $methodData['instructions'] ?? '' }}</div>
                                        @if(!empty($methodData['account_data']))
                                            <div class="mt-1">{{ $methodData['account_data'] }}</div>
                                        @endif
                                    </div>
                                @endif

                                <div class="d-flex justify-content-end gap-2 mt-3">
                                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                                        @lang('driver::home.cancel')
                                    </button>
                                    <button type="submit" class="btn btn-primary"
                                            wire:loading.attr="disabled" wire:target="submit,settlementProof"
                                            @if(!$selectedCollectionIds || $mixedRecipients || !$availableMethods) disabled @endif>
                                        @lang('driver::home.send_for_review')
                                    </button>
                                </div>
                            </form>
                        @else
                            <div class="alert alert-warning">@lang('driver::home.settlement_method_unavailable')</div>
                            <div class="d-flex justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">@lang('driver::home.cancel')</button>
                            </div>
                        @endif
                    @endif
                </div>
                @if($showBatchDetails)
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('driver::home.cancel')</button>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>
