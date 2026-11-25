@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    .precise-form-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        padding: 30px;
        margin-bottom: 30px;
    }

    .form-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
    }

    .form-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a202c;
        margin: 0;
    }

    .precise-form-group {
        margin-bottom: 20px;
    }

    .precise-form-group label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #5d6778;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .precise-form-group input {
        width: 100%;
        padding: 12px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-size: 14px;
        color: #2d3748;
        background: #fff;
        transition: all 0.2s;
    }

    .precise-form-group input:focus {
        border-color: #306FAA;
        box-shadow: 0 0 0 3px rgba(48, 111, 170, 0.1);
        outline: none;
    }

    .precise-form-group input::placeholder {
        color: #a0aec0;
    }

    .section-divider {
        margin: 30px 0;
        border-top: 1px dashed #e2e8f0;
    }

    .form-actions {
        display: flex;
        gap: 15px;
        margin-top: 10px;
    }

    .btn-save {
        padding: 12px 24px;
        background: var(--yellow);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 700;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        display: inline-flex;
        align-items: center;
        gap: 8px;
    }

    .btn-save:hover {
        background: var(--yellow-deep);
        transform: translateY(-1px);
        color: #fff;
    }

    .btn-cancel {
        padding: 12px 24px;
        background: #fff;
        color: #666;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        font-weight: 600;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
    }

    .btn-cancel:hover {
        background: #f8f9fa;
        color: #333;
        border-color: #cbd5e0;
    }

    .helper-text {
        font-size: 12px;
        color: #718096;
        margin-top: 6px;
        display: block;
    }

    .content-header {
        flex-wrap: wrap;
        gap: 12px;
    }
</style>

<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.personal_info')</h2>
        <button type="button" class="btn-save password-change-trigger" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
            <i class="ti-key"></i> @lang('ecommerce::ecommerce.change_password')
        </button>
    </div>

    <div class="precise-form-card">
        <div class="form-header">
            <h4 class="form-title">@lang('ecommerce::ecommerce.edit_profile')</h4>
        </div>

        <form action="{{ route('account.update-profile') }}" 
              method="POST"
              up-submit
              up-target=".dashboard-content">
            @csrf
            
            <div class="row">
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.name') <span class="text-danger">*</span></label>
                        <input type="text" name="name" value="{{ old('name', $user->name) }}" required placeholder="@lang('ecommerce::ecommerce.enter_your_name')">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.email') <span class="text-danger">*</span></label>
                        <input type="email" dir="ltr" name="email" value="{{ old('email', $user->email) }}" readonly required placeholder="@lang('ecommerce::ecommerce.enter_email_address')" style="background-color:#f4f6f9;cursor:not-allowed;">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.phone')</label>
                        <input type="text" dir="ltr" name="phone" value="{{ old('phone', $user->phone) }}" placeholder="@lang('ecommerce::ecommerce.enter_phone_number')">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.date_of_birth')</label>
                        <input type="date" name="date_of_birth" value="{{ old('date_of_birth', $user->date_of_birth?->format('Y-m-d')) }}">
                    </div>
                </div>
            </div>

            <div class="form-actions">
                <button type="submit" class="btn-save">
                    <i class="ti-check"></i> @lang('ecommerce::ecommerce.save_changes')
                </button>
                <a href="{{ route('account.profile') }}" 
                   class="btn-cancel"
                   up-target=".dashboard-content">
                    @lang('ecommerce::ecommerce.cancel')
                </a>
            </div>
        </form>
    </div>
</div>

<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-labelledby="changePasswordModalLabel" aria-hidden="true" @if($errors->has('current_password') || $errors->has('password') || $errors->has('password_confirmation')) data-open-on-error="true" @endif>
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="changePasswordForm" action="{{ route('account.update-password') }}" method="POST" novalidate>
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="changePasswordModalLabel">@lang('ecommerce::ecommerce.change_password')</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="@lang('ecommerce::ecommerce.close')"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-danger d-none password-modal-form-error" data-password-form-error role="alert"></div>
                    <div class="mb-3">
                        <label for="modal-current-password" class="form-label">@lang('ecommerce::ecommerce.current_password')</label>
                        <input id="modal-current-password" type="password" name="current_password" class="form-control @error('current_password') is-invalid @enderror" autocomplete="current-password" required>
                        <small class="text-danger password-modal-error" data-password-error="current_password">@error('current_password'){{ $message }}@enderror</small>
                    </div>
                    <div class="mb-3">
                        <label for="modal-new-password" class="form-label">@lang('ecommerce::ecommerce.new_password')</label>
                        <input id="modal-new-password" type="password" name="password" class="form-control @error('password') is-invalid @enderror" autocomplete="new-password" required>
                        <small class="text-danger password-modal-error" data-password-error="password">@error('password'){{ $message }}@enderror</small>
                    </div>
                    <div class="mb-3">
                        <label for="modal-confirm-password" class="form-label">@lang('ecommerce::ecommerce.confirm_password')</label>
                        <input id="modal-confirm-password" type="password" name="password_confirmation" class="form-control @error('password_confirmation') is-invalid @enderror" autocomplete="new-password" required>
                        <small class="text-danger password-modal-error" data-password-error="password_confirmation">@error('password_confirmation'){{ $message }}@enderror</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">@lang('ecommerce::ecommerce.cancel')</button>
                    <button type="submit" class="password-modal-submit">
                        <i class="ti-check password-modal-idle-icon" aria-hidden="true"></i>
                        <span>@lang('ecommerce::ecommerce.save_changes')</span>
                        <i class="fa fa-spinner fa-spin password-modal-spinner d-none" aria-hidden="true"></i>
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
