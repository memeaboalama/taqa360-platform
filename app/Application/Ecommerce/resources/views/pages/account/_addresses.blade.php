@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    /* VARIABLES & RESET */
    :root {
        --primary: #306FAA;
        --primary-hover: #245C8E;
        --radius: 12px;
    }

    /* PRECISE CARD STYLE (Matching Orders/Wallet) */
    .precise-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: var(--radius);
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        padding: 25px;
        margin-bottom: 25px;
        transition: all 0.2s;
        height: 100%;
        display: flex;
        flex-direction: column;
        justify-content: space-between;
    }

    .precise-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.05);
        border-color: #e2e8f0;
    }

    .address-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 15px;
        padding-bottom: 15px;
        border-bottom: 1px dashed #f0f0f0;
    }

    .address-type {
        font-size: 16px;
        font-weight: 800;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .default-badge {
        background: #fff8e1;
        color: #306FAA;
        font-size: 10px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .address-actions-btn {
        background: transparent;
        border: none;
        color: #a0aec0;
        cursor: pointer;
        padding: 5px;
        transition: color 0.2s;
    }
    .address-actions-btn:hover { color: #dc3545; }

    .address-body {
        font-size: 14px;
        color: #4a5568;
        line-height: 1.6;
        margin-bottom: 20px;
        flex-grow: 1;
    }

    .address-meta {
        display: flex;
        gap: 15px;
        font-size: 13px;
        color: #718096;
        margin-top: 10px;
    }
    .address-meta span { display: flex; align-items: center; gap: 5px; }
    .address-meta i { color: #cbd5e0; }

    /* Add New Card */
    .add-new-card {
        background: #fff;
        border: 2px dashed #e2e8f0;
        border-radius: var(--radius);
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        height: 100%;
        min-height: 220px;
        cursor: pointer;
        transition: all 0.2s;
        text-decoration: none;
    }

    .add-new-card:hover {
        border-color: #306FAA;
        background: #F1F6FB;
    }

    .add-icon-circle {
        width: 50px;
        height: 50px;
        background: #f8f9fa;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 15px;
        transition: all 0.2s;
    }
    .add-new-card:hover .add-icon-circle {
        background: #306FAA;
        color: #fff;
    }
    .add-icon-circle i { font-size: 20px; color: #a0aec0; }
    .add-new-card:hover .add-icon-circle i { color: #fff; }

    .add-text {
        font-weight: 700;
        color: #4a5568;
        font-size: 15px;
    }
    .add-new-card:hover .add-text { color: #1a202c; }

    .btn-edit {
        padding: 8px 16px;
        background: #fff;
        border: 1px solid #e2e8f0;
        border-radius: 6px;
        font-size: 13px;
        font-weight: 600;
        color: #4a5568;
        text-decoration: none;
        display: inline-block;
        transition: all 0.2s;
        width: 100%;
        text-align: center;
    }
    .btn-edit:hover {
        border-color: #306FAA;
        color: #306FAA;
        box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    }
</style>

<div class="account-content-area w-100">
    <div class="content-header mb-4 d-flex justify-content-between align-items-center">
        <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.addresses')</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success mb-4 rounded-3 d-flex align-items-center gap-2">
            <i class="ti-check-box"></i> {{ session('success') }}
        </div>
    @endif

    <div class="row g-4">
        <!-- Add New Address Card -->
        <div class="col-md-6 col-lg-4">
            <a href="{{ route('account.create_address') }}" class="add-new-card" up-target=".dashboard-content">
                <div class="add-icon-circle">
                    <i class="ti-plus"></i>
                </div>
                <span class="add-text">@lang('ecommerce::ecommerce.add_new_address')</span>
            </a>
        </div>

        <!-- Address List -->
        @foreach($addresses as $address)
            <div class="col-md-6 col-lg-4">
                <div class="precise-card">
                    <div>
                        <div class="address-header">
                            <div class="address-type">
                                <span>{{ $address->title ?? __('ecommerce::ecommerce.home') }}</span>
                                @if($address->is_default)
                                    <span class="default-badge">@lang('ecommerce::ecommerce.default')</span>
                                @endif
                            </div>
                            
                            @if(!$address->is_default)
                                <form action="{{ route('account.delete_address', $address->id) }}" method="POST" onsubmit="return confirm('@lang('ecommerce::ecommerce.are_you_sure')');" style="margin:0;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="address-actions-btn" title="@lang('ecommerce::ecommerce.delete')">
                                        <i class="ti-trash"></i>
                                    </button>
                                </form>
                            @else
                                <div style="width: 24px;"></div> <!-- Spacer -->
                            @endif
                        </div>

                        <div class="address-body">
                            {{ $address->address }}
                        </div>

                        <div class="address-meta">
                            <span><i class="ti-map"></i> {{ $address->city->title ?? '' }}, {{ $address->country->title ?? '' }}</span>
                        </div>
                    </div>

                    <div style="margin-top: 20px; padding-top: 15px; border-top: 1px solid #f7fafc;">
                        <a href="{{ route('account.edit_address', $address->id) }}" class="btn-edit" up-target=".dashboard-content">
                            @lang('ecommerce::ecommerce.edit')
                        </a>
                    </div>
                </div>
            </div>
        @endforeach
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
