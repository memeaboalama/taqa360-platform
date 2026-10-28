@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    .service-request-details-card {
        background: #fff;
        border: 1px solid #eef2f5;
        border-radius: 12px;
        box-shadow: 0 4px 20px rgba(0,0,0,0.02);
        overflow: hidden;
        padding: 30px;
    }

    .details-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        gap: 20px;
        border-bottom: 1px solid #f1f4f8;
        padding-bottom: 20px;
        margin-bottom: 25px;
    }

    .status-badge {
        padding: 6px 14px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }
    .status-badge::before {
        content: '';
        display: block;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background-color: currentColor;
    }

    .bg-pending, .bg-warning { background-color: #fff8e1 !important; color: #f57f17 !important; }
    .bg-approved, .bg-completed { background-color: #e8f5e9 !important; color: #2e7d32 !important; }
    .bg-cancelled, .bg-rejected { background-color: #ffebee !important; color: #c62828 !important; }
    .bg-assigned, .bg-in-progress { background-color: #e3f2fd !important; color: #1565c0 !important; }
    .bg-draft { background-color: #f3e5f5 !important; color: #7b1fa2 !important; }

    .service-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 12px;
        border-radius: 30px;
        font-size: 12px;
        font-weight: 600;
        background-color: #eef4fa;
        color: #245C8E;
        border: 1px solid #d8e6f2;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
        gap: 18px;
        margin-bottom: 25px;
    }

    .info-item {
        background: #f8f9fa;
        border: 1px solid #eef2f5;
        border-radius: 10px;
        padding: 14px 16px;
    }

    .info-item .label {
        font-size: 12px;
        color: #718096;
        font-weight: 600;
        margin-bottom: 6px;
    }

    .info-item .value {
        font-size: 15px;
        color: #1a202c;
        font-weight: 700;
        word-break: break-word;
    }

    .section-title {
        font-size: 15px;
        font-weight: 700;
        color: #1a202c;
        margin-bottom: 12px;
        display: flex;
        align-items: center;
        gap: 8px;
    }

    .description-box {
        background: #f8f9fa;
        border: 1px solid #eef2f5;
        border-radius: 10px;
        padding: 18px;
        font-size: 14px;
        color: #2d3748;
        line-height: 1.8;
        white-space: pre-line;
    }

    .rejection-box {
        background: #ffebee;
        border: 1px solid #ffcdd2;
        border-radius: 10px;
        padding: 16px 18px;
        font-size: 14px;
        color: #c62828;
        margin-top: 15px;
    }

    .attachment-link {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        border: 1px solid #e2e8f0;
        border-radius: 8px;
        color: #1565c0 !important;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        background: #fff;
        margin: 0 8px 8px 0;
        transition: all 0.2s;
    }
    .attachment-link:hover { border-color: #1565c0; background: #e3f2fd; }

    .btn-back {
        padding: 8px 18px;
        border-radius: 8px;
        border: 1px solid #e2e8f0;
        color: #4a5568;
        font-size: 13px;
        font-weight: 600;
        transition: all 0.2s;
        background: #fff;
        text-decoration: none;
        display: inline-block;
    }
    .btn-back:hover {
        border-color: #306FAA;
        color: #fff;
        background: #306FAA;
    }

    @media (max-width: 768px) {
        .details-header { flex-direction: column; }
        .service-request-details-card { padding: 20px; }
    }
</style>

@php
    $srStatus = $serviceRequest->status instanceof \UnitEnum ? $serviceRequest->status : null;
    $statusValue = $srStatus?->value ?? (string) $serviceRequest->status;
    $statusClass = match ($statusValue) {
        'draft' => 'bg-draft',
        'approved' => 'bg-approved',
        'assigned' => 'bg-assigned',
        'rejected' => 'bg-rejected',
        'in-progress' => 'bg-in-progress',
        'completed' => 'bg-completed',
        'cancelled' => 'bg-cancelled',
        default => 'bg-pending',
    };

    $educationLevels = [
        'Elementary' => __('ecommerce::ecommerce.level_elementary'),
        'Middle' => __('ecommerce::ecommerce.level_middle'),
        'HighSchool' => __('ecommerce::ecommerce.level_high_school'),
        'Diploma' => __('lang.academic_degree.diploma'),
        'Bachelor' => __('lang.academic_degree.bachelor'),
        'Master' => __('lang.academic_degree.master'),
        'Doctorate' => __('lang.academic_degree.doctorate'),
    ];

    $urgencyLevels = [
        'normal' => __('ecommerce::ecommerce.urgency_normal'),
        'urgent' => __('ecommerce::ecommerce.urgency_urgent'),
        'very_urgent' => __('ecommerce::ecommerce.urgency_very_urgent'),
    ];
@endphp

<div class="account-content-area w-100">
    <div class="service-requests-container">

        <div class="content-header mb-4 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.service_request_details')</h2>
            <a href="{{ route('account.service_requests') }}" class="btn-back" up-target=".dashboard-content">
                <i class="ti-arrow-left arrow-start me-1"></i> @lang('ecommerce::ecommerce.back_to_service_requests')
            </a>
        </div>

        <div class="service-request-details-card">
            <div class="details-header">
                <div>
                    <div class="d-flex align-items-center gap-3 mb-2">
                        <span class="request-code num" style="font-weight:800; color:#1a202c; font-family:monospace; font-size:18px;">{{ $serviceRequest->code }}</span>
                        <span class="service-type-badge">
                            <i class="ti-settings"></i> {{ $serviceRequest->type instanceof \UnitEnum ? $serviceRequest->type->trans() : $serviceRequest->type }}
                        </span>
                    </div>
                    <h4 style="font-size:19px; font-weight:700; color:#1a202c; margin:0;">{{ $serviceRequest->title }}</h4>
                </div>
                <span class="status-badge {{ $statusClass }}">
                    {{ $srStatus?->trans() ?? $serviceRequest->status }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.date')</div>
                    <div class="value">{{ \Carbon\Carbon::parse($serviceRequest->created_at)->format('d M Y - h:i A') }}</div>
                </div>
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.budget')</div>
                    <div class="value">{{ number_format($serviceRequest->budget ?? 0, 2) }} SAR</div>
                </div>
                @if(!is_null($serviceRequest->agreed_amount))
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.agreed_amount')</div>
                        <div class="value">{{ number_format($serviceRequest->agreed_amount, 2) }} SAR</div>
                    </div>
                @endif
                @if($serviceRequest->education_level)
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.education_level')</div>
                        <div class="value">{{ $educationLevels[$serviceRequest->education_level] ?? $serviceRequest->education_level }}</div>
                    </div>
                @endif
                @if($serviceRequest->academicMajor)
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.academic_major')</div>
                        <div class="value">{{ $serviceRequest->academicMajor->name }}</div>
                    </div>
                @endif
                @if($serviceRequest->urgency_level)
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.urgency_level')</div>
                        <div class="value">{{ $urgencyLevels[$serviceRequest->urgency_level] ?? $serviceRequest->urgency_level }}</div>
                    </div>
                @endif
            </div>

            <div class="mb-4">
                <div class="section-title"><i class="ti-file"></i> @lang('ecommerce::ecommerce.description')</div>
                <div class="description-box">{{ $serviceRequest->description }}</div>
            </div>

            @if($serviceRequest->additional_notes)
                <div class="mb-4">
                    <div class="section-title"><i class="ti-comment"></i> @lang('ecommerce::ecommerce.additional_notes')</div>
                    <div class="description-box">{{ $serviceRequest->additional_notes }}</div>
                </div>
            @endif

            @if($serviceRequest->attachments->count() > 0)
                <div class="mb-4">
                    <div class="section-title"><i class="ti-paperclip"></i> @lang('ecommerce::ecommerce.attachments') ({{ $serviceRequest->attachments->count() }})</div>
                    <div>
                        @foreach($serviceRequest->attachments as $file)
                            <a href="{{ $file->url }}" target="_blank" class="attachment-link">
                                <i class="{{ str_starts_with((string)$file->mime_type, 'image/') ? 'ti-image' : 'ti-file' }}"></i>
                                {{ $file->original_name ?? $file->file_name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($statusValue === 'rejected' && $serviceRequest->rejection_reason)
                <div class="rejection-box">
                    <strong>@lang('ecommerce::ecommerce.rejection_reason'):</strong> {{ $serviceRequest->rejection_reason }}
                </div>
            @endif
        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
