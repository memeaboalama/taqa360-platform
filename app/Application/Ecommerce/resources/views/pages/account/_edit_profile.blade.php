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
        background: #306FAA;
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
        background: #245C8E;
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
</style>

<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.personal_info')</h2>
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

            <div class="section-divider"></div>

            <div class="form-header border-0 pb-0 mb-4">
                <h4 class="form-title">@lang('ecommerce::ecommerce.change_password')</h4>
            </div>
            
            <div class="row">
                <div class="col-md-12">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.current_password')</label>
                        <input type="password" name="current_password" placeholder="••••••••">
                        <span class="helper-text">
                            <i class="ti-info-alt"></i> @lang('ecommerce::ecommerce.leave_blank_if_not_changing')
                        </span>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.new_password')</label>
                        <input type="password" name="password" placeholder="••••••••">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="precise-form-group">
                        <label>@lang('ecommerce::ecommerce.confirm_password')</label>
                        <input type="password" name="password_confirmation" placeholder="••••••••">
                    </div>
                </div>
            </div>

            <div class="section-divider"></div>

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

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
