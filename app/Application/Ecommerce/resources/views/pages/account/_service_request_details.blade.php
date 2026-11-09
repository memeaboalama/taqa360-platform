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

    .timeline-list {
        display: grid;
        gap: 12px;
    }

    .timeline-item {
        border-inline-start: 3px solid #d8e6f2;
        padding: 10px 14px;
        background: #f8f9fa;
        border-radius: 8px;
    }

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
        'pending' => 'bg-pending',
        'approved' => 'bg-approved',
        'assigned' => 'bg-assigned',
        'rejected' => 'bg-rejected',
        'in-progress' => 'bg-in-progress',
        'completed' => 'bg-completed',
        'cancelled' => 'bg-cancelled',
        default => 'bg-pending',
    };
    $statusColor = static fn ($value) => match ($value) {
        'draft' => 'bg-draft',
        'pending', 'unpaid' => 'bg-pending',
        'approved', 'completed', 'paid' => 'bg-approved',
        'assigned', 'in-progress', 'in_progress', 'partially_paid', 'partial' => 'bg-assigned',
        'rejected', 'cancelled', 'canceled' => 'bg-rejected',
        default => 'bg-pending',
    };
    $statusLabel = static function ($value) {
        $normalized = str_replace('-', '_', (string) $value);
        if ($normalized === 'canceled') {
            $normalized = 'cancelled';
        }

        return __('lang.educational_request.status.' . $normalized);
    };
    $executionStatus = $serviceRequest->execution_status;
    $executionStatusValue = $executionStatus instanceof \UnitEnum
        ? $executionStatus->value
        : (string) $executionStatus;
    $paymentStatus = $serviceRequest->payment_status;
    $paymentStatusValue = $paymentStatus instanceof \UnitEnum
        ? $paymentStatus->value
        : (string) $paymentStatus;
    $agreedAmount = $serviceRequest->agreed_amount;
    $paidAmount = match ($paymentStatusValue) {
        'paid' => $agreedAmount,
        'unpaid' => 0,
        default => null,
    };
    $remainingAmount = match ($paymentStatusValue) {
        'paid' => 0,
        'unpaid' => $agreedAmount,
        default => null,
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

    $timelineEvents = collect();
    $timelineEvents->push([
        'at' => $serviceRequest->created_at,
        'title' => __('ecommerce::ecommerce.request_submitted'),
    ]);

    if ($serviceRequest->reviewed_at) {
        $timelineEvents->push([
            'at' => $serviceRequest->reviewed_at,
            'title' => __('ecommerce::ecommerce.request_reviewed', [
                'status' => $srStatus?->trans() ?? $serviceRequest->status,
            ]),
        ]);
    }

    foreach ([
        'accepted_at' => 'request_accepted',
        'rejected_at' => 'request_rejected',
        'completed_at' => 'request_completed',
    ] as $timestampField => $translationKey) {
        if ($serviceRequest->{$timestampField}) {
            $timelineEvents->push([
                'at' => $serviceRequest->{$timestampField},
                'title' => __('ecommerce::ecommerce.' . $translationKey),
            ]);
        }
    }

    $activityTitles = [
        'project.updated' => 'activity_project_updated',
        'project.status_changed' => 'activity_project_status_changed',
        'project.published' => 'activity_project_published',
        'project.unpublished' => 'activity_project_unpublished',
        'task.created' => 'activity_task_created',
        'task.updated' => 'activity_task_updated',
        'task.status_changed' => 'activity_task_status_changed',
        'task.educator_assigned' => 'activity_provider_assigned',
        'task.assignment_rejected' => 'activity_assignment_rejected',
        'task.assignment_cancelled' => 'activity_assignment_cancelled',
    ];

    $project = $serviceRequest->project;
    if ($project) {
        if ($project->created_at) {
            $timelineEvents->push([
                'at' => $project->created_at,
                'title' => __('ecommerce::ecommerce.project_created'),
            ]);
        }

        foreach ($project->activityLog as $log) {
            if (isset($activityTitles[$log->message_key]) && $log->timestamp) {
                $timelineEvents->push([
                    'at' => $log->timestamp,
                    'title' => __('ecommerce::ecommerce.' . $activityTitles[$log->message_key]),
                    'actor' => $log->actor,
                ]);
            }
        }

        foreach ($project->tasks as $task) {
            foreach ($task->activityLog as $log) {
                if (isset($activityTitles[$log->message_key]) && $log->timestamp) {
                    $timelineEvents->push([
                        'at' => $log->timestamp,
                        'title' => __('ecommerce::ecommerce.' . $activityTitles[$log->message_key]),
                        'actor' => $log->actor_name,
                    ]);
                }
            }

            foreach ($task->acceptedAssignments as $assignment) {
                if ($assignment->accepted_at) {
                    $timelineEvents->push([
                        'at' => $assignment->accepted_at,
                        'title' => __('ecommerce::ecommerce.assignment_accepted'),
                        'actor' => $assignment->educator?->name,
                    ]);
                }
            }
        }
    }

    $timelineEvents = $timelineEvents->filter(fn ($event) => $event['at'])->sortByDesc('at');
@endphp

<div class="account-content-area w-100">
    <div class="service-requests-container">

        <div class="content-header mb-4 d-flex justify-content-between align-items-center">
            <h2 class="fw-bold mb-0" style="font-size: 24px; color: #1a202c;">@lang('ecommerce::ecommerce.service_request_details')</h2>
            <a href="{{ route('account.service_requests') }}" class="btn-back">
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
                    <small class="text-muted d-block mt-2">{{ \Carbon\Carbon::parse($serviceRequest->created_at)->format('d M Y - h:i A') }}</small>
                </div>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.request_status')</div>
                    <div class="value"><span class="status-badge {{ $statusClass }}">{{ $srStatus?->trans() ?? $serviceRequest->status }}</span></div>
                </div>
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.execution_status')</div>
                    <div class="value">
                        <span class="status-badge {{ $statusColor($executionStatusValue) }}">
                            {{ $executionStatus instanceof \UnitEnum ? $executionStatus->trans() : __('lang.educational_request.status.' . str_replace('-', '_', $executionStatusValue)) }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.payment_status')</div>
                    <div class="value">
                        <span class="status-badge {{ $statusColor($paymentStatusValue) }}">
                            {{ $paymentStatusValue !== '' ? __('lang.educational_request.payment_status.' . $paymentStatusValue) : __('ecommerce::ecommerce.not_started') }}
                        </span>
                    </div>
                </div>
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.expected_budget')</div>
                    <div class="value">{{ number_format($serviceRequest->budget ?? 0, 2) }} SAR</div>
                </div>
                @if(!is_null($serviceRequest->agreed_amount))
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.agreed_amount')</div>
                        <div class="value">{{ number_format($serviceRequest->agreed_amount, 2) }} SAR</div>
                    </div>
                @endif
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.amount_paid')</div>
                    <div class="value">{{ is_null($paidAmount) ? __('ecommerce::ecommerce.amount_not_recorded') : number_format($paidAmount, 2) . ' SAR' }}</div>
                </div>
                <div class="info-item">
                    <div class="label">@lang('ecommerce::ecommerce.remaining_amount')</div>
                    <div class="value">{{ is_null($remainingAmount) ? __('ecommerce::ecommerce.amount_not_recorded') : number_format($remainingAmount, 2) . ' SAR' }}</div>
                </div>
                @if(!is_null($serviceRequest->project_site_type))
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.sr_project_site_type')</div>
                        <div class="value">{{ $serviceRequest->project_site_type }}</div>
                    </div>
                @endif
                @if(!is_null($serviceRequest->solar_system_type))
                    <div class="info-item">
                        <div class="label">@lang('ecommerce::ecommerce.sr_solar_system_type')</div>
                        <div class="value">{{ $serviceRequest->solar_system_type }}</div>
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

            @if($project)
                <div class="mb-4">
                    <div class="section-title"><i class="ti-briefcase"></i> @lang('ecommerce::ecommerce.linked_project')</div>
                    <div class="info-grid">
                        <div class="info-item">
                            <div class="label">@lang('ecommerce::ecommerce.project_title')</div>
                            <div class="value">{{ $project->title }}</div>
                        </div>
                        <div class="info-item">
                            <div class="label">@lang('ecommerce::ecommerce.project_status')</div>
                            <div class="value">{{ $statusLabel($project->status instanceof \UnitEnum ? $project->status->value : $project->status) }}</div>
                        </div>
                        @if($project->deadline)
                            <div class="info-item">
                                <div class="label">@lang('ecommerce::ecommerce.deadline')</div>
                                <div class="value">{{ $project->deadline->format('Y-m-d') }}</div>
                            </div>
                        @endif
                    </div>

                    @if($project->tasks->isNotEmpty())
                        <h5 class="section-title mt-3">@lang('ecommerce::ecommerce.project_tasks')</h5>
                        @foreach($project->tasks as $task)
                            <div class="info-item mb-2">
                                <div class="d-flex justify-content-between align-items-start gap-3">
                                    <div>
                                        <div class="value">{{ $task->title }}</div>
                                        @foreach($task->acceptedAssignments as $assignment)
                                            @if($assignment->educator)
                                                <small class="text-muted d-block mt-1">
                                                    @lang('ecommerce::ecommerce.service_provider'): {{ $assignment->educator->name }}
                                                </small>
                                            @endif
                                        @endforeach
                                    </div>
                                    @php($taskStatusValue = $task->status instanceof \UnitEnum ? $task->status->value : (string) $task->status)
                                    <span class="status-badge {{ $statusColor($taskStatusValue) }}">
                                        {{ $statusLabel($taskStatusValue) }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>
            @endif

            @if($timelineEvents->isNotEmpty())
                <div class="mb-4">
                    <div class="section-title"><i class="ti-time"></i> @lang('ecommerce::ecommerce.request_timeline')</div>
                    <div class="timeline-list">
                        @foreach($timelineEvents as $event)
                            <div class="timeline-item">
                                <strong>{{ $event['title'] }}</strong>
                                <small class="text-muted d-block">{{ \Carbon\Carbon::parse($event['at'])->format('d M Y - h:i A') }}</small>
                                @if(!empty($event['actor']))
                                    <small class="text-muted d-block">{{ $event['actor'] }}</small>
                                @endif
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>
</div>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
