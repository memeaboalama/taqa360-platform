<div>
    <div class="card custom-card">
        <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
            <h5 class="card-title mb-0">{{ __('admin::home.bank_account_requests') }}</h5>
        </div>
        <div class="card-body">
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fs-12">{{ __('admin::home.search') }}</label>
                    <input type="text" class="form-control" wire:model.debounce.400ms="search"
                           placeholder="{{ __('admin::home.search_bank_requests_placeholder') }}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fs-12">{{ __('admin::home.status') }}</label>
                    <select class="form-select" wire:model="status">
                        <option value="">{{ __('admin::home.all') }}</option>
                        <option value="pending_review">{{ __('admin::home.bank_status_pending_review') }}</option>
                        <option value="approved">{{ __('admin::home.bank_status_approved') }}</option>
                        <option value="rejected">{{ __('admin::home.bank_status_rejected') }}</option>
                        <option value="cancelled">{{ __('admin::home.bank_status_cancelled') }}</option>
                        <option value="draft">{{ __('admin::home.bank_status_draft') }}</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12">{{ __('admin::home.date_from') }}</label>
                    <input type="date" class="form-control" wire:model="date_from">
                </div>
                <div class="col-md-2">
                    <label class="form-label fs-12">{{ __('admin::home.date_to') }}</label>
                    <input type="date" class="form-control" wire:model="date_to">
                </div>
                <div class="col-md-1 d-flex align-items-end">
                    <button type="button" class="btn btn-light w-100" wire:click="resetFilters" title="{{ __('admin::home.reset') }}">
                        <i class="ri-refresh-line"></i>
                    </button>
                </div>
            </div>

            <div class="table-responsive">
                <table class="table table-hover table-bordered align-middle">
                    <thead class="table-light">
                    <tr>
                        <th>{{ __('admin::home.request_number') }}</th>
                        <th>{{ __('admin::home.merchant_store') }}</th>
                        <th>{{ __('admin::home.bank_name') }}</th>
                        <th>{{ __('admin::home.iban') }}</th>
                        <th>{{ __('admin::home.submitted_by') }}</th>
                        <th>{{ __('admin::home.date') }}</th>
                        <th>{{ __('admin::home.status') }}</th>
                        <th>{{ __('admin::home.Action') }}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @forelse($requests as $req)
                        @php
                            $badgeClass = match($req->status) {
                                'approved' => 'bg-success-transparent text-success',
                                'pending_review' => 'bg-warning-transparent text-warning',
                                'rejected' => 'bg-danger-transparent text-danger',
                                'cancelled' => 'bg-secondary-transparent text-secondary',
                                default => 'bg-light text-dark',
                            };
                        @endphp
                        <tr>
                            <td class="fw-semibold">{{ $req->request_number }}</td>
                            <td>
                                @if($req->merchant)
                                    <a href="{{ route('admin:merchant.show', $req->merchant_id) }}" class="text-primary fw-semibold">
                                        {{ $req->merchant->company_name ?: $req->merchant->name }}
                                    </a>
                                @else
                                    #{{ $req->merchant_id }}
                                @endif
                            </td>
                            <td>{{ $req->bank_name }}</td>
                            <td class="font-monospace" dir="ltr">{{ $req->masked_iban }}</td>
                            <td>{{ $req->submitter_name }}</td>
                            <td>{{ ($req->submitted_at ?? $req->created_at)?->format('Y-m-d H:i') }}</td>
                            <td>
                                <span class="badge {{ $badgeClass }}">
                                    {{ __('admin::home.bank_status_' . $req->status) }}
                                </span>
                            </td>
                            <td>
                                <a href="{{ route('admin:merchant.bank-accounts.show', $req->id) }}" class="btn btn-sm btn-primary-light">
                                    <i class="ri-eye-line me-1"></i>{{ __('admin::home.review_request') }}
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted py-4">
                                {{ __('admin::home.no_bank_account_requests') }}
                            </td>
                        </tr>
                    @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-3">
                {{ $requests->links() }}
            </div>
        </div>
    </div>
</div>
