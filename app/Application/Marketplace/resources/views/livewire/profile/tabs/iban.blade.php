<div class="tab-pane fade p-0 border-0" id="iban-tab-pane"
     role="tabpanel" aria-labelledby="iban-tab" tabindex="0" wire:ignore.self>
    <div class="mt-4">
        {{-- 1. Approved Bank Account Card --}}
        <div class="card custom-card border mb-4">
            <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h5 class="card-title mb-0">{{ __('marketplace::home.bank_account_and_iban_title') }}</h5>
                    <small class="text-muted">{{ __('marketplace::home.bank_account_subtitle') }}</small>
                </div>
                @if(!$pendingRequest && !$showForm)
                    @MerchantHasRole(['edit iban info_profile'])
                    <button type="button" class="btn btn-primary" wire:click="openCreateForm">
                        <i class="ri-bank-card-line me-1"></i>
                        {{ $approvedIban ? __('marketplace::home.request_change_account') : __('marketplace::home.add_bank_account') }}
                    </button>
                    @endMerchantHasRole
                @endif
            </div>
            <div class="card-body">
                @if($approvedIban)
                    <div class="row g-3 align-items-center">
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.bank_name') }}</span>
                            <span class="fw-semibold fs-15">{{ $approvedIban->bank_name }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.beneficiary_full_name') }}</span>
                            <span class="fw-semibold fs-15">{{ $approvedIban->beneficiary_full_name }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.iban') }}</span>
                            <span class="fw-semibold fs-15 font-monospace" dir="ltr">{{ $approvedIban->masked_iban }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.approved_at') }}</span>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge bg-success-transparent text-success">{{ __('marketplace::home.status_approved') }}</span>
                                <span class="fs-13">{{ $approvedIban->approved_at?->format('Y-m-d H:i') ?? $approvedIban->updated_at?->format('Y-m-d') }}</span>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning mb-0 d-flex align-items-center justify-content-between flex-wrap gap-2">
                        <div>
                            <i class="ri-information-line me-1"></i>
                            {{ __('marketplace::home.no_approved_bank_account') }}
                        </div>
                    </div>
                @endif
            </div>
        </div>

        {{-- 2. Rejected Request Banner (when no pending request is active) --}}
        @if(!$pendingRequest && $latestRejectedRequest && !$showForm)
            <div class="alert alert-danger border mb-4">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <h6 class="alert-heading mb-1">
                            <i class="ri-close-circle-line me-1"></i>
                            {{ __('marketplace::home.last_request_rejected', ['number' => $latestRejectedRequest->request_number]) }}
                        </h6>
                        <p class="mb-1">
                            <strong>{{ __('marketplace::home.rejection_reason') }}:</strong>
                            {{ $latestRejectedRequest->rejection_reason }}
                        </p>
                        <small class="text-muted">
                            {{ __('marketplace::home.reviewed_at') }}: {{ $latestRejectedRequest->reviewed_at?->format('Y-m-d H:i') }}
                        </small>
                    </div>
                    @MerchantHasRole(['edit iban info_profile'])
                    <button type="button" class="btn btn-sm btn-danger"
                            wire:click="openResubmitForm({{ $latestRejectedRequest->id }})">
                        <i class="ri-refresh-line me-1"></i>{{ __('marketplace::home.resubmit_corrected_request') }}
                    </button>
                    @endMerchantHasRole
                </div>
            </div>
        @endif

        {{-- 3. Active Pending Request Card (Read-only + Cancel button) --}}
        @if($pendingRequest)
            <div class="card custom-card border-warning mb-4">
                <div class="card-header bg-warning-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <div>
                        <h6 class="card-title mb-0">
                            {{ __('marketplace::home.current_pending_request') }}
                            <span class="badge bg-warning text-dark ms-2">{{ __('marketplace::home.status_pending_review') }}</span>
                        </h6>
                        <small class="text-muted">{{ $pendingRequest->request_number }} — {{ $pendingRequest->submitted_at?->format('Y-m-d H:i') }}</small>
                    </div>
                    @MerchantHasRole(['edit iban info_profile'])
                    <button type="button"
                            class="btn btn-outline-danger btn-sm"
                            wire:click="cancelPendingRequest({{ $pendingRequest->id }})"
                            wire:loading.attr="disabled">
                        <i class="ri-close-line me-1"></i>{{ __('marketplace::home.cancel_request') }}
                    </button>
                    @endMerchantHasRole
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.bank_name') }}</span>
                            <span class="fw-semibold">{{ $pendingRequest->bank_name }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.beneficiary_full_name') }}</span>
                            <span class="fw-semibold">{{ $pendingRequest->beneficiary_full_name }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.iban') }}</span>
                            <span class="fw-semibold font-monospace" dir="ltr">{{ $pendingRequest->iban }}</span>
                        </div>
                        <div class="col-md-3">
                            <span class="text-muted d-block fs-12">{{ __('marketplace::home.Iban File') }}</span>
                            <a href="{{ route('marketplace:bank-account.certificate', $pendingRequest->id) }}"
                               target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary mt-1">
                                <i class="ri-file-text-line me-1"></i>{{ __('marketplace::home.view_certificate') }}
                            </a>
                        </div>
                        @if($pendingRequest->change_reason)
                            <div class="col-12">
                                <span class="text-muted d-block fs-12">{{ __('marketplace::home.change_reason') }}</span>
                                <span>{{ $pendingRequest->change_reason }}</span>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        {{-- 4. Add / Change Bank Account Form (with Preview Step) --}}
        @if($showForm && !$pendingRequest)
            <div class="card custom-card border-primary mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h6 class="card-title mb-0">
                        {{ $approvedIban ? __('marketplace::home.request_change_account') : __('marketplace::home.add_bank_account') }}
                    </h6>
                    <button type="button" class="btn btn-sm btn-light" wire:click="closeForm">
                        <i class="ri-close-line"></i>
                    </button>
                </div>
                <div class="card-body">
                    @if(!$showPreview)
                        <form wire:submit.prevent="reviewPreview" enctype="multipart/form-data">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="bank_name" class="form-label">{{ __('marketplace::home.bank_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="bank_name"
                                           class="form-control @error('bank_name') is-invalid @enderror"
                                           wire:model.defer="bank_name"
                                           placeholder="{{ __('marketplace::home.bank_name') }}">
                                    @error('bank_name') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="beneficiary_full_name" class="form-label">{{ __('marketplace::home.beneficiary_full_name') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="beneficiary_full_name"
                                           class="form-control @error('beneficiary_full_name') is-invalid @enderror"
                                           wire:model.defer="beneficiary_full_name"
                                           placeholder="{{ __('marketplace::home.beneficiary_full_name') }}">
                                    @error('beneficiary_full_name') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="iban" class="form-label">{{ __('marketplace::home.iban') }} <span class="text-danger">*</span></label>
                                    <input type="text" id="iban" dir="ltr" maxlength="24"
                                           class="form-control font-monospace text-uppercase @error('iban') is-invalid @enderror"
                                           wire:model.defer="iban"
                                           placeholder="SA0000000000000000000000">
                                    <small class="text-muted">{{ __('marketplace::home.saudi_iban_hint') }}</small>
                                    @error('iban') <span class="text-danger d-block fs-12">{{ $message }}</span> @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="iban_file" class="form-label">{{ __('marketplace::home.Iban File') }} <span class="text-danger">*</span></label>
                                    <input type="file" id="iban_file"
                                           class="form-control @error('iban_file') is-invalid @enderror"
                                           wire:model="iban_file"
                                           accept=".pdf,.jpg,.jpeg,.png">
                                    <small class="text-muted">{{ __('marketplace::home.iban_certificate_hint') }}</small>
                                    @error('iban_file') <span class="text-danger d-block fs-12">{{ $message }}</span> @enderror
                                </div>

                                @if($approvedIban)
                                    <div class="col-12">
                                        <label for="change_reason" class="form-label">{{ __('marketplace::home.change_reason') }} <span class="text-danger">*</span></label>
                                        <textarea id="change_reason" rows="2"
                                                  class="form-control @error('change_reason') is-invalid @enderror"
                                                  wire:model.defer="change_reason"
                                                  placeholder="{{ __('marketplace::home.change_reason_placeholder') }}"></textarea>
                                        @error('change_reason') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                                    </div>
                                @endif
                            </div>

                            <div class="d-flex justify-content-end gap-2 mt-4">
                                <button type="button" class="btn btn-light" wire:click="closeForm">{{ __('marketplace::home.cancel') }}</button>
                                <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="reviewPreview,iban_file">
                                    {{ __('marketplace::home.preview_before_submit') }}
                                </button>
                            </div>
                        </form>
                    @else
                        <div class="alert alert-info">
                            <h6 class="mb-2">{{ __('marketplace::home.confirm_bank_request_summary') }}</h6>
                            <div class="row g-2">
                                <div class="col-md-6"><strong>{{ __('marketplace::home.bank_name') }}:</strong> {{ $bank_name }}</div>
                                <div class="col-md-6"><strong>{{ __('marketplace::home.beneficiary_full_name') }}:</strong> {{ $beneficiary_full_name }}</div>
                                <div class="col-md-6"><strong>{{ __('marketplace::home.iban') }}:</strong> <span class="font-monospace" dir="ltr">{{ $iban }}</span></div>
                                <div class="col-md-6"><strong>{{ __('marketplace::home.Iban File') }}:</strong> {{ $iban_file?->getClientOriginalName() }}</div>
                                @if($change_reason)
                                    <div class="col-12"><strong>{{ __('marketplace::home.change_reason') }}:</strong> {{ $change_reason }}</div>
                                @endif
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2 mt-3">
                            <button type="button" class="btn btn-light" wire:click="backToEdit" wire:loading.attr="disabled">
                                {{ __('marketplace::home.back_to_edit') }}
                            </button>
                            <button type="button" class="btn btn-success" wire:click="submitRequest" wire:loading.attr="disabled" wire:target="submitRequest">
                                <span wire:loading.class="d-none" wire:target="submitRequest">
                                    {{ __('marketplace::home.submit_for_review') }}
                                </span>
                                <span class="d-none" wire:loading.class.remove="d-none" wire:target="submitRequest">
                                    @lang('marketplace::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                                </span>
                            </button>
                        </div>
                    @endif
                </div>
            </div>
        @endif

        {{-- 5. Previous Requests History --}}
        <div class="card custom-card border">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('marketplace::home.previous_requests_history') }}</h6>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead>
                        <tr>
                            <th>{{ __('marketplace::home.request_number') }}</th>
                            <th>{{ __('marketplace::home.bank_name') }}</th>
                            <th>{{ __('marketplace::home.iban') }}</th>
                            <th>{{ __('marketplace::home.status') }}</th>
                            <th>{{ __('marketplace::home.date') }}</th>
                            <th>{{ __('marketplace::home.notes') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @forelse($requestHistory as $item)
                            @php
                                $badgeClass = match($item->status) {
                                    'approved' => 'bg-success-transparent text-success',
                                    'pending_review' => 'bg-warning-transparent text-warning',
                                    'rejected' => 'bg-danger-transparent text-danger',
                                    'cancelled' => 'bg-secondary-transparent text-secondary',
                                    default => 'bg-light text-dark',
                                };
                            @endphp
                            <tr>
                                <td class="fw-semibold">{{ $item->request_number }}</td>
                                <td>{{ $item->bank_name }}</td>
                                <td class="font-monospace" dir="ltr">{{ $item->masked_iban }}</td>
                                <td>
                                    <span class="badge {{ $badgeClass }}">
                                        {{ __('marketplace::home.status_' . $item->status) }}
                                    </span>
                                </td>
                                <td>{{ ($item->submitted_at ?? $item->created_at)?->format('Y-m-d H:i') }}</td>
                                <td>
                                    @if($item->rejection_reason)
                                        <span class="text-danger">{{ $item->rejection_reason }}</span>
                                    @elseif($item->change_reason)
                                        <span class="text-muted">{{ $item->change_reason }}</span>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-4">
                                    {{ __('marketplace::home.no_previous_requests') }}
                                </td>
                            </tr>
                        @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
