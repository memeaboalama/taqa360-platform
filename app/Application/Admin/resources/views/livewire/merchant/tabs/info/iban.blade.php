<div class="mt-4">
    {{-- 1. Current Active Account --}}
    <div class="card custom-card border mb-4">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <div>
                <h5 class="card-title mb-0">{{ __('admin::home.current_approved_bank_account') }}</h5>
                <small class="text-muted">{{ __('marketplace::home.bank_account_subtitle') }}</small>
            </div>
            @if(!$pendingRequest && !$showAdminCreateForm)
                <button type="button" class="btn btn-primary-light" wire:click="openAdminCreateForm">
                    <i class="ri-user-add-line me-1"></i>{{ __('admin::home.create_request_on_behalf') }}
                </button>
            @endif
        </div>
        <div class="card-body">
            @if($approvedIban)
                <div class="row g-3 align-items-center">
                    <div class="col-md-3">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.bank_name') }}</span>
                        <span class="fw-semibold fs-15">{{ $approvedIban->bank_name }}</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.beneficiary_full_name') }}</span>
                        <span class="fw-semibold fs-15">{{ $approvedIban->beneficiary_full_name }}</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.iban') }}</span>
                        <span class="fw-semibold fs-15 font-monospace" dir="ltr">{{ $approvedIban->iban }}</span>
                    </div>
                    <div class="col-md-3">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.approved_at') }}</span>
                        <span class="fs-13">{{ $approvedIban->approved_at?->format('Y-m-d H:i') ?? $approvedIban->updated_at?->format('Y-m-d') }}</span>
                    </div>
                </div>
            @else
                <div class="alert alert-warning mb-0">
                    <i class="ri-information-line me-1"></i>{{ __('marketplace::home.no_approved_bank_account') }}
                </div>
            @endif
        </div>
    </div>

    {{-- 2. Pending Request Alert (if any) --}}
    @if($pendingRequest)
        <div class="card custom-card border-warning mb-4">
            <div class="card-header bg-warning-transparent d-flex justify-content-between align-items-center flex-wrap gap-2">
                <div>
                    <h6 class="card-title mb-0">
                        {{ __('marketplace::home.current_pending_request') }}: {{ $pendingRequest->request_number }}
                        <span class="badge bg-warning text-dark ms-2">{{ __('marketplace::home.status_pending_review') }}</span>
                    </h6>
                    <small class="text-muted">
                        {{ __('admin::home.submitted_by') }}: {{ $pendingRequest->submitter_name }} — {{ $pendingRequest->submitted_at?->format('Y-m-d H:i') }}
                    </small>
                </div>
                <a href="{{ route('admin:merchant.bank-accounts.show', $pendingRequest->id) }}" class="btn btn-warning btn-sm">
                    <i class="ri-search-eye-line me-1"></i>{{ __('admin::home.review_request') }}
                </a>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-4">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.bank_name') }}</span>
                        <span class="fw-semibold">{{ $pendingRequest->bank_name }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.beneficiary_full_name') }}</span>
                        <span class="fw-semibold">{{ $pendingRequest->beneficiary_full_name }}</span>
                    </div>
                    <div class="col-md-4">
                        <span class="text-muted d-block fs-12">{{ __('admin::home.iban') }}</span>
                        <span class="fw-semibold font-monospace" dir="ltr">{{ $pendingRequest->iban }}</span>
                    </div>
                    @if($pendingRequest->change_reason)
                        <div class="col-12">
                            <span class="text-muted d-block fs-12">{{ __('admin::home.change_reason') }}</span>
                            <span>{{ $pendingRequest->change_reason }}</span>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    @endif

    {{-- 3. Admin create on behalf form --}}
    @if($showAdminCreateForm && !$pendingRequest)
        <div class="card custom-card border-primary mb-4">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h6 class="card-title mb-0">{{ __('admin::home.create_request_on_behalf') }}</h6>
                <button type="button" class="btn btn-sm btn-light" wire:click="closeAdminCreateForm">
                    <i class="ri-close-line"></i>
                </button>
            </div>
            <div class="card-body">
                <div class="alert alert-info py-2 mb-3 fs-13">
                    <i class="ri-information-line me-1"></i>
                    {{ __('admin::home.dual_control_admin_notice') }}
                </div>
                <form wire:submit.prevent="submitOnBehalf" enctype="multipart/form-data">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin::home.bank_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('admin_bank_name') is-invalid @enderror"
                                   wire:model.defer="admin_bank_name">
                            @error('admin_bank_name') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin::home.beneficiary_full_name') }} <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('admin_beneficiary_full_name') is-invalid @enderror"
                                   wire:model.defer="admin_beneficiary_full_name">
                            @error('admin_beneficiary_full_name') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('admin::home.iban') }} <span class="text-danger">*</span></label>
                            <input type="text" dir="ltr" maxlength="24"
                                   class="form-control font-monospace text-uppercase @error('admin_iban') is-invalid @enderror"
                                   wire:model.defer="admin_iban" placeholder="SA0000000000000000000000">
                            @error('admin_iban') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">{{ __('marketplace::home.Iban File') }} <span class="text-danger">*</span></label>
                            <input type="file" class="form-control @error('admin_iban_file') is-invalid @enderror"
                                   wire:model="admin_iban_file" accept=".pdf,.jpg,.jpeg,.png">
                            @error('admin_iban_file') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                        </div>
                        <div class="col-12">
                            <label class="form-label">{{ __('admin::home.reason_for_action') }} <span class="text-danger">*</span></label>
                            <textarea rows="2" class="form-control @error('admin_reason') is-invalid @enderror"
                                      wire:model.defer="admin_reason"
                                      placeholder="{{ __('admin::home.reason_for_action_placeholder') }}"></textarea>
                            @error('admin_reason') <span class="text-danger fs-12">{{ $message }}</span> @enderror
                        </div>
                    </div>
                    <div class="d-flex justify-content-end gap-2 mt-3">
                        <button type="button" class="btn btn-light" wire:click="closeAdminCreateForm">{{ __('marketplace::home.cancel') }}</button>
                        <button type="submit" class="btn btn-primary" wire:loading.attr="disabled" wire:target="submitOnBehalf,admin_iban_file">
                            <span wire:loading.class="d-none" wire:target="submitOnBehalf">
                                {{ __('admin::home.submit_request') }}
                            </span>
                            <span class="d-none" wire:loading.class.remove="d-none" wire:target="submitOnBehalf">
                                @lang('admin::home.Wait') <span class="spinner-border spinner-border-sm align-middle ms-1"></span>
                            </span>
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    {{-- 4. History Table --}}
    <div class="card custom-card border">
        <div class="card-header">
            <h6 class="card-title mb-0">{{ __('marketplace::home.previous_requests_history') }}</h6>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead>
                    <tr>
                        <th>{{ __('admin::home.request_number') }}</th>
                        <th>{{ __('admin::home.bank_name') }}</th>
                        <th>{{ __('admin::home.iban') }}</th>
                        <th>{{ __('admin::home.submitted_by') }}</th>
                        <th>{{ __('admin::home.status') }}</th>
                        <th>{{ __('admin::home.date') }}</th>
                        <th>{{ __('admin::home.Action') }}</th>
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
                            <td>{{ $item->submitter_name }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ __('admin::home.bank_status_' . $item->status) }}
                                </span>
                            </td>
                            <td>{{ ($item->submitted_at ?? $item->created_at)?->format('Y-m-d H:i') }}</td>
                            <td>
                                <a href="{{ route('admin:merchant.bank-accounts.show', $item->id) }}" class="btn btn-sm btn-primary-light">
                                    <i class="ri-eye-line me-1"></i>{{ __('admin::home.review') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center text-muted py-3">
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
