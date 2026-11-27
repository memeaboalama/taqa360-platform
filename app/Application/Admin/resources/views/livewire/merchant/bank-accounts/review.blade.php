<div>
    @php
        $badgeClass = match($bankRequest->status) {
            'approved' => 'bg-success-transparent text-success',
            'pending_review' => 'bg-warning-transparent text-warning',
            'rejected' => 'bg-danger-transparent text-danger',
            'cancelled' => 'bg-secondary-transparent text-secondary',
            default => 'bg-light text-dark',
        };
        $isPdf = str_contains((string) $bankRequest->iban_file_mime, 'pdf')
            || strtolower(pathinfo((string) $bankRequest->iban_file, PATHINFO_EXTENSION)) === 'pdf';
        $certUrl = $bankRequest->iban_file
            ? ($bankRequest->iban_file_disk === 'local'
                ? route('admin:merchant.bank-accounts.certificate', $bankRequest->id)
                : \Illuminate\Support\Facades\Storage::disk($bankRequest->iban_file_disk ?: 'fileDisk')->url($bankRequest->iban_file))
            : null;
        $sameAdminCreated = $bankRequest->submitted_by_type === 'admin'
            && (int) $bankRequest->submitted_by_id === (int) auth('admin')->id();
    @endphp

    <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
        <div>
            <h5 class="mb-1">
                {{ __('admin::home.review_bank_account_request') }}: {{ $bankRequest->request_number }}
                <span class="badge {{ $badgeClass }} ms-2">{{ __('admin::home.bank_status_' . $bankRequest->status) }}</span>
            </h5>
            <div class="text-muted fs-13">
                {{ __('admin::home.merchant_store') }}:
                <strong>{{ $bankRequest->merchant?->company_name ?: $bankRequest->merchant?->name }}</strong>
                — {{ __('admin::home.submitted_by') }}: {{ $bankRequest->submitter_name }}
                — {{ ($bankRequest->submitted_at ?? $bankRequest->created_at)?->format('Y-m-d H:i') }}
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin:merchant.bank-accounts.index') }}" class="btn btn-primary-light">
                <i class="ri-arrow-go-back-line me-1"></i>{{ __('admin::home.back_to_bank_requests') }}
            </a>
            @if($bankRequest->merchant)
                <a href="{{ route('admin:merchant.show', $bankRequest->merchant_id) }}" class="btn btn-outline-primary">
                    {{ __('admin::home.showMerchant') }}
                </a>
            @endif
        </div>
    </div>

    {{-- Side-by-side comparison --}}
    <div class="row g-4 mb-4">
        <div class="col-12 col-xl-6">
            <div class="card custom-card h-100 border">
                <div class="card-header bg-light">
                    <h6 class="card-title mb-0">{{ __('admin::home.previous_approved_account') }}</h6>
                </div>
                <div class="card-body">
                    @if($bankRequest->previousIban)
                        <div class="mb-3">
                            <span class="text-muted fs-12 d-block">{{ __('admin::home.bank_name') }}</span>
                            <span class="fw-semibold">{{ $bankRequest->previousIban->bank_name }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted fs-12 d-block">{{ __('admin::home.beneficiary_full_name') }}</span>
                            <span class="fw-semibold">{{ $bankRequest->previousIban->beneficiary_full_name }}</span>
                        </div>
                        <div class="mb-3">
                            <span class="text-muted fs-12 d-block">{{ __('admin::home.iban') }}</span>
                            <span class="fw-semibold font-monospace" dir="ltr">{{ $bankRequest->previousIban->iban }}</span>
                        </div>
                        <div>
                            <span class="text-muted fs-12 d-block">{{ __('admin::home.approved_at') }}</span>
                            <span>{{ $bankRequest->previousIban->approved_at?->format('Y-m-d H:i') ?? '—' }}</span>
                        </div>
                    @else
                        <div class="text-muted py-4 text-center">{{ __('admin::home.no_previous_approved_account') }}</div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-12 col-xl-6">
            <div class="card custom-card h-100 border-primary">
                <div class="card-header bg-primary-transparent">
                    <h6 class="card-title mb-0">{{ __('admin::home.proposed_bank_account') }}</h6>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <span class="text-muted fs-12 d-block">{{ __('admin::home.bank_name') }}</span>
                        <span class="fw-semibold">{{ $bankRequest->bank_name }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted fs-12 d-block">{{ __('admin::home.beneficiary_full_name') }}</span>
                        <span class="fw-semibold">{{ $bankRequest->beneficiary_full_name }}</span>
                    </div>
                    <div class="mb-3">
                        <span class="text-muted fs-12 d-block">{{ __('admin::home.iban') }}</span>
                        <span class="fw-semibold font-monospace" dir="ltr">{{ $bankRequest->iban }}</span>
                    </div>
                    @if($bankRequest->change_reason)
                        <div class="mb-3">
                            <span class="text-muted fs-12 d-block">{{ __('admin::home.change_reason') }}</span>
                            <span>{{ $bankRequest->change_reason }}</span>
                        </div>
                    @endif

                    @if($certUrl)
                        <div class="mt-3">
                            <span class="text-muted fs-12 d-block mb-1">{{ __('marketplace::home.Iban File') }}</span>
                            @if($isPdf)
                                <a href="{{ $certUrl }}" target="_blank" rel="noopener" class="btn btn-outline-primary btn-sm">
                                    <i class="ri-file-pdf-line me-1"></i>{{ __('admin::home.open_iban_certificate') }}
                                </a>
                            @else
                                <a href="{{ $certUrl }}" target="_blank" rel="noopener">
                                    <img src="{{ $certUrl }}" class="img-fluid rounded border" style="max-height: 220px" alt="IBAN Certificate">
                                </a>
                            @endif
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- Decision Section --}}
    @if($bankRequest->status === 'pending_review')
        <div class="card custom-card border mb-4">
            <div class="card-header">
                <h6 class="card-title mb-0">{{ __('admin::home.review_decision') }}</h6>
            </div>
            <div class="card-body">
                @if($sameAdminCreated)
                    <div class="alert alert-warning">
                        <i class="ri-shield-user-line me-1"></i>
                        {{ __('admin::home.dual_control_approval_required') }}
                    </div>
                @endif

                <div class="form-check mb-3">
                    <input class="form-check-input @error('document_verified') is-invalid @enderror"
                           type="checkbox" id="document_verified" wire:model="document_verified">
                    <label class="form-check-label fw-semibold" for="document_verified">
                        {{ __('admin::home.confirm_iban_document_match') }}
                    </label>
                    @error('document_verified') <span class="text-danger d-block fs-12 mt-1">{{ $message }}</span> @enderror
                </div>

                <div class="d-flex gap-2 flex-wrap">
                    @if(!$sameAdminCreated)
                        <button type="button" class="btn btn-success"
                                wire:click="approve"
                                wire:loading.attr="disabled"
                                wire:target="approve">
                            <i class="ri-check-line me-1"></i>{{ __('admin::home.approve_bank_account') }}
                        </button>
                    @endif

                    <button type="button" class="btn btn-outline-danger" wire:click="toggleRejectBox">
                        <i class="ri-close-line me-1"></i>{{ __('admin::home.reject_bank_request') }}
                    </button>
                </div>

                @if($showRejectBox)
                    <div class="mt-3 p-3 border rounded bg-light">
                        <label for="rejection_reason" class="form-label">{{ __('admin::home.rejection_reason') }} <span class="text-danger">*</span></label>
                        <textarea id="rejection_reason" rows="3"
                                  class="form-control @error('rejection_reason') is-invalid @enderror"
                                  wire:model.defer="rejection_reason"
                                  placeholder="{{ __('admin::home.rejection_reason_placeholder') }}"></textarea>
                        @error('rejection_reason') <span class="text-danger d-block fs-12 mt-1">{{ $message }}</span> @enderror
                        <div class="mt-2 d-flex justify-content-end">
                            <button type="button" class="btn btn-danger"
                                    wire:click="reject"
                                    wire:loading.attr="disabled"
                                    wire:target="reject">
                                {{ __('admin::home.confirm_rejection') }}
                            </button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    @elseif($bankRequest->status === 'rejected' && $bankRequest->rejection_reason)
        <div class="alert alert-danger mb-4">
            <strong>{{ __('admin::home.rejection_reason') }}:</strong> {{ $bankRequest->rejection_reason }}
        </div>
    @endif

    {{-- Audit Log --}}
    <div class="card custom-card border">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ __('admin::home.action_audit_log') }}</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>{{ __('admin::home.date') }}</th>
                        <th>{{ __('admin::home.actor') }}</th>
                        <th>{{ __('admin::home.Action') }}</th>
                        <th>{{ __('admin::home.notes') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($bankRequest->logs as $log)
                        <tr>
                            <td>{{ $log->created_at?->format('Y-m-d H:i:s') }}</td>
                            <td>{{ $log->actor_name }}</td>
                            <td><span class="badge bg-light text-dark">{{ $log->action }}</span></td>
                            <td>{{ $log->notes ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted py-3">—</td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
