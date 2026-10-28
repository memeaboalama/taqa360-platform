@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    .precise-info-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        padding: 30px;
        margin-bottom: 30px;
    }

    .info-header {
        margin-bottom: 25px;
        padding-bottom: 15px;
        border-bottom: 1px solid #f0f0f0;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .info-title {
        font-size: 18px;
        font-weight: 800;
        color: #1a202c;
        margin: 0;
    }

    .info-group {
        margin-bottom: 25px;
    }

    .info-label {
        display: block;
        font-size: 13px;
        font-weight: 600;
        color: #5d6778;
        margin-bottom: 6px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .info-value {
        font-size: 16px;
        color: #2d3748;
        font-weight: 500;
        padding: 10px 0;
        border-bottom: 1px dashed #e2e8f0;
    }

    .btn-edit-profile {
        padding: 10px 20px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #4a5568;
        font-size: 14px;
        font-weight: 600;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        transition: all 0.2s;
    }

    .btn-edit-profile:hover {
        border-color: #306FAA;
        color: #306FAA;
        background: #fff;
        box-shadow: 0 4px 12px rgba(0,0,0,0.05);
    }
</style>

<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.personal_info')</h2>
        <a href="{{ route('account.edit-profile') }}" class="btn-edit-profile" up-target=".dashboard-content">
            <i class="ti-pencil"></i> @lang('ecommerce::ecommerce.edit_profile')
        </a>
    </div>

    <div class="precise-info-card">
        <div class="info-header">
            <h4 class="info-title">@lang('ecommerce::ecommerce.profile_details')</h4>
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="info-group">
                    <span class="info-label">@lang('ecommerce::ecommerce.name')</span>
                    <div class="info-value">{{ $user->name }}</div>
                </div>
            </div>
            
            <div class="col-md-6">
                <div class="info-group">
                    <span class="info-label">@lang('ecommerce::ecommerce.email')</span>
                    <div class="info-value ltr">{{ $user->email }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-group">
                    <span class="info-label">@lang('ecommerce::ecommerce.phone')</span>
                    <div class="info-value num">{{ $user->phone ?? '-' }}</div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="info-group">
                    <span class="info-label">@lang('ecommerce::ecommerce.date_of_birth')</span>
                    <div class="info-value">{{ $user->date_of_birth ? $user->date_of_birth->format('d M Y') : '-' }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
