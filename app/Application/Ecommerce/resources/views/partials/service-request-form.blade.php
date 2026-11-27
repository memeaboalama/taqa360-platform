@php
    $academicMajors = config('app.brand') === 'huloolcom' ? \Domain\AppBase\Models\Edu\AcademicMajor::all() : collect();
@endphp

<style>
    .service-request-page {
        min-height: 70vh;
        padding: 34px 0 56px;
        background: #f3f6fa;
    }

    .service-request-page > .container {
        max-width: 1120px;
    }

    .sr-page-heading {
        margin-bottom: 24px;
    }

    .sr-page-heading a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 8px;
        color: var(--yellow);
        font-weight: 700;
        text-decoration: none;
    }

    .sr-page-heading h1 {
        margin: 0;
        color: #172b3a;
        font-size: clamp(25px, 3vw, 32px);
        font-weight: 800;
    }

    .sr-page-heading p {
        margin: 5px 0 0;
        color: #718096;
        font-size: 14px;
    }

    .sr-request-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        align-items: start;
        gap: 22px;
    }

    .sr-page-card {
        overflow: hidden;
        border: 1px solid #e5eaf0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 26px rgba(29, 37, 48, .055);
    }

    .sr-stepper {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        padding: 18px 24px 0;
        border-bottom: 1px solid #edf0f4;
    }

    .sr-stepper-item {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 9px;
        min-width: 0;
        padding: 0 8px 16px;
        border-bottom: 3px solid transparent;
        background: transparent;
        color: #7b8794;
        font-size: 13px;
        font-weight: 700;
        text-align: start;
    }

    .sr-stepper-item:disabled {
        cursor: default;
    }

    .sr-stepper-number {
        display: grid;
        flex: 0 0 30px;
        place-items: center;
        width: 30px;
        height: 30px;
        border: 1px solid #dfe5ec;
        border-radius: 50%;
        background: #f7f9fb;
        color: #738092;
        font-size: 12px;
    }

    .sr-stepper-item.is-active {
        border-color: var(--yellow);
        color: #243746;
    }

    .sr-stepper-item.is-active .sr-stepper-number,
    .sr-stepper-item.is-complete .sr-stepper-number {
        border-color: var(--yellow);
        background: var(--yellow);
        color: #fff;
    }

    .sr-form-body {
        padding: 26px !important;
        background: #fff;
    }

    .sr-step-panel[hidden] {
        display: none !important;
    }

    .sr-section-heading {
        display: flex;
        align-items: flex-start;
        gap: 12px;
        margin-bottom: 23px;
    }

    .sr-section-number {
        display: grid;
        flex: 0 0 38px;
        place-items: center;
        width: 38px;
        height: 38px;
        border-radius: 11px;
        background: {{ $platformBrand['primary_light_color'] }};
        color: {{ $platformBrand['primary_hover_color'] }};
        font-size: 15px;
        font-weight: 800;
    }

    .sr-section-heading h2 {
        margin: 0;
        color: #26394a;
        font-size: 18px;
        font-weight: 800;
    }

    .sr-section-heading p {
        margin: 3px 0 0;
        color: #8793a1;
        font-size: 12px;
    }

    #serviceRequestForm .form-label {
        color: #34495b !important;
        font-size: 13px;
        font-weight: 700 !important;
    }

    #serviceRequestForm .form-control,
    #serviceRequestForm .form-select {
        min-height: 48px;
        border-color: #dfe5ec !important;
        border-radius: 10px !important;
        background-color: #fff;
        color: #253746;
        font-size: 14px !important;
        box-shadow: none;
    }

    #serviceRequestForm textarea.form-control {
        min-height: 116px;
        padding-top: 12px;
        resize: vertical;
    }

    #serviceRequestForm .form-control:focus,
    #serviceRequestForm .form-select:focus {
        border-color: var(--yellow) !important;
        box-shadow: 0 0 0 3px {{ $platformBrand['primary_light_color'] }};
    }

    .sr-form-footer {
        gap: 12px;
        padding: 18px 26px !important;
        border-top: 1px solid #edf0f3;
        background: #fff;
    }

    .sr-form-footer .btn {
        min-height: 46px;
        border-radius: 10px !important;
        font-weight: 700;
    }

    .sr-form-footer .sr-step-actions {
        display: flex;
        gap: 10px;
    }

    .sr-request-aside {
        position: sticky;
        top: 96px;
        padding: 22px;
        border: 1px solid #e5eaf0;
        border-radius: 16px;
        background: #fff;
        box-shadow: 0 8px 26px rgba(29, 37, 48, .055);
    }

    .sr-aside-icon {
        display: grid;
        place-items: center;
        width: 48px;
        height: 48px;
        margin-bottom: 15px;
        border-radius: 14px;
        background: {{ $platformBrand['primary_light_color'] }};
        color: {{ $platformBrand['primary_hover_color'] }};
        font-size: 20px;
    }

    .sr-request-aside h2 {
        margin: 0;
        color: #26394a;
        font-size: 18px;
        font-weight: 800;
    }

    .sr-request-aside > p {
        margin: 8px 0 18px;
        color: #778596;
        font-size: 12px;
        line-height: 1.8;
    }

    .sr-summary-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 12px 0;
        border-top: 1px solid #edf0f4;
        font-size: 12px;
    }

    .sr-summary-row span {
        color: #7b8794;
    }

    .sr-summary-row strong {
        max-width: 60%;
        overflow-wrap: anywhere;
        color: #34495b;
        text-align: end;
    }

    .sr-aside-note {
        margin-top: 16px;
        padding: 12px;
        border-radius: 10px;
        background: #f6f8fa;
        color: #69798a;
        font-size: 11px;
        line-height: 1.8;
    }

    @media (max-width: 900px) {
        .sr-request-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .sr-request-aside {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .service-request-page {
            padding: 20px 0 36px;
        }

        .sr-request-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .sr-form-body {
            padding: 18px !important;
        }

        .sr-form-footer {
            flex-wrap: wrap;
            padding: 14px !important;
        }

        .sr-form-footer .btn {
            padding-inline: 12px !important;
            font-size: .9rem !important;
        }

        .sr-stepper {
            padding: 15px 10px 0;
        }

        .sr-stepper-item {
            flex-direction: column;
            gap: 5px;
            padding: 0 3px 11px;
            font-size: 10px;
            text-align: center;
        }

        .sr-stepper-number {
            flex-basis: 27px;
            width: 27px;
            height: 27px;
        }

        .sr-request-aside {
            position: static;
        }
    }
</style>

<div class="service-request-page">
    <div class="container">
        <header class="sr-page-heading"
            dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'rtl' : 'ltr' }}">
            <a href="{{ route('service-requests.create') }}" up-follow="false">
                <i class="fa fa-arrow-{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'right' : 'left' }}"
                    aria-hidden="true"></i>
                @lang('ecommerce::ecommerce.home')
            </a>
            <h1>@lang('ecommerce::ecommerce.request_service')</h1>
            <p>@lang('ecommerce::ecommerce.sr_page_intro')</p>
        </header>

        <div class="sr-request-layout">
            <section class="sr-page-card">
                <div class="sr-stepper" role="group"
                    aria-label="@lang('ecommerce::ecommerce.sr_page_intro')">
                    <button type="button" class="sr-stepper-item is-active" data-step-target="1"
                        aria-current="step">
                        <span class="sr-stepper-number">1</span>
                        <span>@lang('ecommerce::ecommerce.sr_page_step_basics')</span>
                    </button>
                    <button type="button" class="sr-stepper-item" data-step-target="2" disabled>
                        <span class="sr-stepper-number">2</span>
                        <span>@lang('ecommerce::ecommerce.sr_page_step_details')</span>
                    </button>
                    <button type="button" class="sr-stepper-item" data-step-target="3" disabled>
                        <span class="sr-stepper-number">3</span>
                        <span>@lang('ecommerce::ecommerce.sr_page_step_files')</span>
                    </button>
                </div>

                <form id="serviceRequestForm" action="{{ route('service-requests.store', [], false) }}" method="POST"
                enctype="multipart/form-data" novalidate>
                @csrf
                <style>
                    .sr-field-error {
                        color: #c62828;
                        font-size: 0.8rem;
                        font-weight: 600;
                        margin-top: 4px;
                        display: flex;
                        align-items: center;
                        gap: 4px;
                    }

                    .sr-field-error::before {
                        content: '\f06a';
                        font-family: 'FontAwesome';
                        font-size: 0.85rem;
                    }

                    #serviceRequestForm .form-control.is-invalid,
                    #serviceRequestForm .form-select.is-invalid {
                        border-color: #c62828 !important;
                        background-image: none;
                    }

                    .sr-budget-input-group {
                        border: 1px solid #ced4da;
                        border-radius: 10px;
                        overflow: hidden;
                        background: #fff;
                    }

                    .sr-budget-input-group:focus-within {
                        border-color: #86b7fe;
                        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.15);
                    }

                    .sr-budget-input-group.is-invalid {
                        border-color: #c62828;
                    }

                    .sr-budget-input-group .form-control {
                        border: 0;
                        border-radius: 0;
                        box-shadow: none;
                    }

                    #sr_budget {
                        appearance: textfield;
                        -moz-appearance: textfield;
                    }

                    #sr_budget::-webkit-inner-spin-button,
                    #sr_budget::-webkit-outer-spin-button {
                        -webkit-appearance: none;
                        margin: 0;
                    }

                    .sr-budget-input-group .input-group-text {
                        border: 0;
                        border-inline-start: 1px solid #dee2e6;
                        border-radius: 0;
                        background: #f8f9fa;
                    }

                    /* URGENCY RADIO BUTTONS */
                    .urgency-options {
                        display: flex;
                        gap: 10px;
                    }

                    .urgency-option {
                        flex: 1 1 0;
                        cursor: pointer;
                        margin: 0;
                    }

                    .urgency-option input {
                        display: none;
                    }

                    .urgency-option span {
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 6px;
                        border: 1px solid #e2e8f0;
                        border-radius: 8px;
                        padding: 10px 8px;
                        font-size: 0.9rem;
                        font-weight: 600;
                        color: #4a5568;
                        background: #fff;
                        transition: all 0.2s;
                        white-space: nowrap;
                    }

                    .urgency-option input:checked+span {
                        border-color: {{ $platformBrand['primary_color'] }};
                        background: {{ $platformBrand['primary_light_color'] }};
                        color: {{ $platformBrand['primary_hover_color'] }};
                        box-shadow: 0 0 0 2px {{ $platformBrand['primary_color'] }} inset;
                    }

                    .urgency-option span i {
                        font-size: 0.95rem;
                    }

                    .urgency-option input[value="urgent"]:checked+span {
                        border-color: #EA993E;
                        color: #EA993E;
                        box-shadow: 0 0 0 2px #EA993E inset;
                    }

                    .urgency-option input[value="very_urgent"]:checked+span {
                        border-color: #e53e3e;
                        background: #fff5f5;
                        color: #c53030;
                        box-shadow: 0 0 0 2px #e53e3e inset;
                    }
                </style>

                <div class="sr-form-body p-4">

                    <div id="serviceRequestAlert" class="alert d-none" role="alert"></div>

                    <section class="sr-step-panel" data-step-panel="1">
                        <div class="sr-section-heading">
                            <span class="sr-section-number">1</span>
                            <div>
                                <h2>@lang('ecommerce::ecommerce.sr_page_step_basics')</h2>
                                <p>@lang('ecommerce::ecommerce.sr_page_summary_help')</p>
                            </div>
                        </div>
                    <div class="row g-3">
                        <!-- Title -->
                        <div class="col-12">
                            <label for="sr_title" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.sr_request_title') <span class="text-danger">*</span>
                            </label>
                            <input type="text" class="form-control form-control-lg border-2" id="sr_title"
                                name="title" required placeholder="@lang('ecommerce::ecommerce.sr_title_placeholder')"
                                style="border-radius: 10px; font-size: 0.95rem;">
                        </div>

                        @if (config('app.brand') === 'huloolcom')
                            <div class="col-md-6">
                                <label for="sr_education_level" class="form-label font-weight-bold text-dark mb-1">
                                    @lang('ecommerce::ecommerce.education_level')
                                </label>
                                <select class="form-select form-select-lg border-2" id="sr_education_level"
                                    name="education_level" style="border-radius: 10px; font-size: 0.95rem;">
                                    <option value="">@lang('ecommerce::ecommerce.sr_select_education_level')</option>
                                    <option value="Elementary">@lang('ecommerce::ecommerce.level_elementary')</option>
                                    <option value="Middle">@lang('ecommerce::ecommerce.level_middle')</option>
                                    <option value="HighSchool">@lang('ecommerce::ecommerce.level_high_school')</option>
                                    <option value="Diploma">@lang('lang.academic_degree.diploma')</option>
                                    <option value="Bachelor" selected>@lang('lang.academic_degree.bachelor')</option>
                                    <option value="Master">@lang('lang.academic_degree.master')</option>
                                    <option value="Doctorate">@lang('lang.academic_degree.doctorate')</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="sr_academic_major_id" class="form-label font-weight-bold text-dark mb-1">
                                    @lang('ecommerce::ecommerce.academic_major')
                                </label>
                                <select class="form-select form-select-lg border-2" id="sr_academic_major_id"
                                    name="academic_major_id" style="border-radius: 10px; font-size: 0.95rem;">
                                    <option value="">@lang('ecommerce::ecommerce.sr_select_major')</option>
                                    @foreach ($academicMajors as $major)
                                        <option value="{{ $major->id }}">{{ $major->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        @else
                            <!-- Project Site Type -->
                            <div class="col-12 col-md-6">
                                <label for="sr_project_site_type" class="form-label font-weight-bold text-dark mb-1">
                                    @lang('ecommerce::ecommerce.sr_project_site_type')
                                </label>
                                <div class="custom-dd w-100" id="cd_project">
                                    <button type="button" class="form-select form-select-lg border-2 text-start"
                                        style="border-radius: 10px; font-size: 0.95rem;"
                                        onclick="toggleDD('cd_project')">
                                        <span id="cd_project_label">اختر نوع الموقع</span>
                                    </button>
                                    <div class="custom-dd-menu" id="cd_project_menu" style="display:none;">
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','','اختر نوع الموقع')">اختر
                                            نوع الموقع</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','residential','سكني')">سكني</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','commercial','تجاري')">تجاري</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','agricultural','زراعي/مزرعة')">زراعي/مزرعة</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','industrial','صناعي')">صناعي</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','medical','مرفق طبي')">مرفق
                                            طبي</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','educational','مرفق تعليمي')">مرفق
                                            تعليمي</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_project','sr_project_site_type','other','أخرى')">أخرى</a>
                                    </div>
                                    <input type="hidden" name="project_site_type" id="sr_project_site_type"
                                        value="">
                                </div>
                            </div>
                            <!-- Solar System Type -->
                            <div class="col-12 col-md-6">
                                <label for="sr_solar_system_type" class="form-label font-weight-bold text-dark mb-1">
                                    @lang('ecommerce::ecommerce.sr_solar_system_type')
                                </label>
                                <div class="custom-dd w-100" id="cd_solar">
                                    <button type="button" class="form-select form-select-lg border-2 text-start"
                                        style="border-radius: 10px; font-size: 0.95rem;"
                                        onclick="toggleDD('cd_solar')">
                                        <span id="cd_solar_label">اختر نوع النظام</span>
                                    </button>
                                    <div class="custom-dd-menu" id="cd_solar_menu" style="display:none;">
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_solar','sr_solar_system_type','','اختر نوع النظام')">اختر
                                            نوع النظام</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_solar','sr_solar_system_type','off-grid','خارج الشبكة (Off-Grid)')">خارج
                                            الشبكة (Off-Grid)</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_solar','sr_solar_system_type','on-grid','متصل بالشبكة (On-Grid)')">متصل
                                            بالشبكة (On-Grid)</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_solar','sr_solar_system_type','hybrid','هجين (Hybrid)')">هجين
                                            (Hybrid)</a>
                                        <a href="javascript:void(0)"
                                            onclick="pickDD('cd_solar','sr_solar_system_type','not_sure','غير متأكد – أحتاج استشارة فنية')">غير
                                            متأكد – أحتاج استشارة فنية</a>
                                    </div>
                                    <input type="hidden" name="solar_system_type" id="sr_solar_system_type"
                                        value="">
                                </div>
                            </div>
                        @endif

                        <!-- Request Type -->
                        <div class="col-md-6">
                            <label for="sr_type" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.service_type')
                            </label>
                            <select class="form-select form-select-lg border-2" id="sr_type" name="service_id"
                                data-selected-service="{{ request('service') }}" required disabled
                                style="border-radius: 10px; font-size: 0.95rem;">
                                <option value="">@lang('ecommerce::ecommerce.loading')</option>
                            </select>
                        </div>
                    </div>
                    </section>

                    <section class="sr-step-panel" data-step-panel="2" hidden>
                        <div class="sr-section-heading">
                            <span class="sr-section-number">2</span>
                            <div>
                                <h2>@lang('ecommerce::ecommerce.sr_page_step_details')</h2>
                                <p>@lang('ecommerce::ecommerce.sr_urgency_hint')</p>
                            </div>
                        </div>
                    <div class="row g-3">
                        <!-- Budget -->
                        <div class="col-md-6">
                            <label for="sr_budget" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.sr_budget_label')
                            </label>
                            <div class="input-group sr-budget-input-group"
                                dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'rtl' : 'ltr' }}">
                                <input type="number" dir="ltr" step="0.01" min="0"
                                    class="form-control form-control-lg" id="sr_budget" name="budget"
                                    placeholder="@lang('ecommerce::ecommerce.sr_budget_placeholder')"
                                    style="font-size: 0.95rem;">
                                <span class="input-group-text">@lang('ecommerce::ecommerce.currency_symbol')</span>
                            </div>
                        </div>

                        <!-- Urgency Level (Radio Buttons) -->
                        <div class="col-12">
                            <label class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.urgency_level')
                            </label>
                            <div class="urgency-options">
                                <label class="urgency-option">
                                    <input type="radio" name="urgency_level" value="normal" checked>
                                    <span><i class="ti-check"></i> @lang('ecommerce::ecommerce.urgency_normal')</span>
                                </label>
                                <label class="urgency-option">
                                    <input type="radio" name="urgency_level" value="urgent">
                                    <span><i class="ti-bolt"></i> @lang('ecommerce::ecommerce.urgency_urgent')</span>
                                </label>
                                <label class="urgency-option">
                                    <input type="radio" name="urgency_level" value="very_urgent">
                                    <span><i class="ti-alert"></i> @lang('ecommerce::ecommerce.urgency_very_urgent')</span>
                                </label>
                            </div>
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                @lang('ecommerce::ecommerce.sr_urgency_hint')
                            </div>
                        </div>

                        <!-- Description -->
                        <div class="col-12">
                            <label for="sr_description" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.sr_description') <span class="text-danger">*</span>
                            </label>
                            <textarea class="form-control border-2" id="sr_description" name="description" rows="4" required
                                placeholder="@lang('ecommerce::ecommerce.sr_description_placeholder')" style="border-radius: 10px; font-size: 0.95rem;"></textarea>
                        </div>

                        <!-- Additional Notes -->
                        <div class="col-12">
                            <label for="sr_additional_notes" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.additional_notes')
                            </label>
                            <textarea class="form-control border-2" id="sr_additional_notes" name="additional_notes" rows="2"
                                placeholder="@lang('ecommerce::ecommerce.sr_notes_placeholder')" style="border-radius: 10px; font-size: 0.95rem;"></textarea>
                        </div>
                    </div>
                    </section>

                    <section class="sr-step-panel" data-step-panel="3" hidden>
                        <div class="sr-section-heading">
                            <span class="sr-section-number">3</span>
                            <div>
                                <h2>@lang('ecommerce::ecommerce.sr_page_step_files')</h2>
                                <p>@lang('ecommerce::ecommerce.sr_attachments_hint')</p>
                            </div>
                        </div>
                    <div class="row g-3">
                        <!-- Attachments -->
                        <div class="col-12">
                            <label for="sr_attachments" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.sr_attachments_label')
                            </label>
                            <input class="form-control border-2" type="file" id="sr_attachments"
                                name="attachments[]" multiple style="border-radius: 10px; font-size: 0.9rem;">
                            <div class="form-text text-muted" style="font-size: 0.8rem;">
                                @lang('ecommerce::ecommerce.sr_attachments_hint')
                            </div>
                        </div>
                    </div>
                    </section>

                </div>

                <div class="sr-form-footer p-3 d-flex justify-content-between">
                    <a href="{{ route('service-requests.create') }}" class="btn btn-light btn-lg px-4 border"
                        style="border-radius: 10px; font-size: 0.95rem;">
                        @lang('ecommerce::ecommerce.sr_cancel')
                    </a>
                    <div class="sr-step-actions">
                        <button type="button" class="btn btn-light border" id="sr_previous_btn" hidden>
                            @lang('ecommerce::ecommerce.sr_page_previous')
                        </button>
                        <button type="button" class="btn btn-primary" id="sr_next_btn">
                            @lang('ecommerce::ecommerce.sr_page_next')
                        </button>
                    <button type="submit" id="sr_submit_btn" class="btn btn-danger btn-lg px-5 font-weight-bold"
                        hidden
                        style="background: #306FAA;border-color: #306FAA;color: #ffffff;; border-radius: 10px; font-size: 0.95rem;">
                        <span id="sr_submit_spinner" class="spinner-border spinner-border-sm me-2 d-none"
                            role="status" aria-hidden="true"></span>
                        <i class="fa fa-paper-plane me-1"></i> @lang('ecommerce::ecommerce.sr_submit')
                    </button>
                    </div>
                </div>
            </form>
            </section>

            <aside class="sr-request-aside">
                <span class="sr-aside-icon"><i class="fa fa-file-text-o" aria-hidden="true"></i></span>
                <h2>@lang('ecommerce::ecommerce.sr_page_summary_title')</h2>
                <p>@lang('ecommerce::ecommerce.sr_page_summary_help')</p>
                <div class="sr-summary-row">
                    <span>@lang('ecommerce::ecommerce.sr_page_summary_service')</span>
                    <strong id="sr-summary-service">@lang('ecommerce::ecommerce.sr_select_service')</strong>
                </div>
                <div class="sr-summary-row">
                    <span>@lang('ecommerce::ecommerce.sr_page_summary_budget')</span>
                    <strong id="sr-summary-budget">—</strong>
                </div>
                <div class="sr-summary-row">
                    <span>@lang('ecommerce::ecommerce.sr_page_summary_urgency')</span>
                    <strong id="sr-summary-urgency">—</strong>
                </div>
                <div class="sr-aside-note">@lang('ecommerce::ecommerce.sr_attachments_hint')</div>
            </aside>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceRequestForm');
        const alertBox = document.getElementById('serviceRequestAlert');
        const submitBtn = document.getElementById('sr_submit_btn');
        const submitSpinner = document.getElementById('sr_submit_spinner');
        const nextBtn = document.getElementById('sr_next_btn');
        const previousBtn = document.getElementById('sr_previous_btn');
        const stepPanels = Array.from(form?.querySelectorAll('[data-step-panel]') || []);
        const stepButtons = Array.from(document.querySelectorAll('[data-step-target]'));
        let activeStep = 1;

        const messages = {
            titleRequired: @json(__('ecommerce::ecommerce.sr_title_required')),
            descriptionRequired: @json(__('ecommerce::ecommerce.sr_description_required')),
            descriptionMin: @json(__('ecommerce::ecommerce.sr_description_min')),
            serviceRequired: @json(__('ecommerce::ecommerce.sr_service_required')),
            budgetInvalid: @json(__('ecommerce::ecommerce.sr_budget_invalid')),
            successMsg: @json(__('ecommerce::ecommerce.sr_success_msg')),
            submitError: @json(__('ecommerce::ecommerce.sr_submit_error')),
            connectionError: @json(__('ecommerce::ecommerce.sr_connection_error')),
            selectService: @json(__('ecommerce::ecommerce.sr_select_service')),
            noServices: @json(__('ecommerce::ecommerce.sr_no_services')),
            servicesError: @json(__('ecommerce::ecommerce.sr_services_error')),
            loading: @json(__('ecommerce::ecommerce.loading')),
        };

        if (!form) return;

        const serviceSelect = document.getElementById('sr_type');
        const summaryService = document.getElementById('sr-summary-service');
        const summaryBudget = document.getElementById('sr-summary-budget');
        const summaryUrgency = document.getElementById('sr-summary-urgency');
        let servicesLoading = false;
        let servicesError = false;

        function setActiveStep(step) {
            activeStep = step;
            stepPanels.forEach(function(panel) {
                panel.hidden = Number(panel.dataset.stepPanel) !== activeStep;
            });
            stepButtons.forEach(function(button) {
                const buttonStep = Number(button.dataset.stepTarget);
                button.disabled = buttonStep > activeStep;
                button.classList.toggle('is-active', buttonStep === activeStep);
                button.classList.toggle('is-complete', buttonStep < activeStep);
                if (buttonStep === activeStep) {
                    button.setAttribute('aria-current', 'step');
                } else {
                    button.removeAttribute('aria-current');
                }
            });
            previousBtn.hidden = activeStep === 1;
            nextBtn.hidden = activeStep === 3;
            submitBtn.hidden = activeStep !== 3;
        }

        function updateSummary() {
            const selectedService = serviceSelect.selectedOptions[0];
            summaryService.textContent = serviceSelect.value && selectedService ?
                selectedService.textContent : messages.selectService;

            const budget = document.getElementById('sr_budget').value.trim();
            summaryBudget.textContent = budget ?
                `${budget} ${@json(__('ecommerce::ecommerce.currency_symbol'))}` : '—';

            const urgency = form.querySelector('input[name="urgency_level"]:checked');
            summaryUrgency.textContent = urgency ?
                urgency.nextElementSibling.textContent.trim() : '—';
        }

        setActiveStep(1);
        updateSummary();

        stepButtons.forEach(function(button) {
            button.addEventListener('click', function() {
                const targetStep = Number(button.dataset.stepTarget);
                if (targetStep < activeStep) setActiveStep(targetStep);
            });
        });

        previousBtn.addEventListener('click', function() {
            if (activeStep > 1) setActiveStep(activeStep - 1);
        });

        nextBtn.addEventListener('click', function() {
            if (validateForm(activeStep)) {
                setActiveStep(activeStep + 1);
                document.querySelector('.sr-page-card').scrollIntoView({
                    behavior: 'smooth',
                    block: 'start'
                });
            }
        });

        form.addEventListener('input', updateSummary);
        form.addEventListener('change', updateSummary);

        async function loadServices() {
            if (servicesLoading) return;
            servicesLoading = true;
            servicesError = false;
            const selected = serviceSelect.dataset.selectedService || serviceSelect.value;
            serviceSelect.disabled = true;
            serviceSelect.replaceChildren(new Option(messages.loading, ''));

            try {
                const response = await fetch(@json(route('service-requests.services', [], false)), {
                    headers: {
                        'Accept': 'application/json'
                    }
                });

                if (!response.ok) throw new Error();

                const result = await response.json();
                const services = result.data?.services;

                if (!Array.isArray(services)) throw new Error();

                serviceSelect.replaceChildren(new Option(
                    services.length ? messages.selectService : messages.noServices, ''
                ));

                const locale = @json(app()->getLocale());

                services.forEach(function(service) {
                    const name = typeof service.name === 'string' ? service.name :
                        (service.name?.[locale] || service.name?.ar || service.name?.en || '');

                    serviceSelect.add(new Option(name, service.id));
                });

                if (services.some(service => String(service.id) === String(selected))) {
                    serviceSelect.value = selected;
                }
                delete serviceSelect.dataset.selectedService;

                serviceSelect.disabled = services.length === 0;
            } catch (error) {
                servicesError = true;
                serviceSelect.replaceChildren(new Option(messages.servicesError, ''));
            } finally {
                servicesLoading = false;
                updateSummary();
            }
        }

        loadServices();

        function clearFieldErrors() {
            form.querySelectorAll('.sr-field-error').forEach(function(el) {
                el.remove();
            });

            form.querySelectorAll('.is-invalid').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
            form.querySelectorAll('.sr-budget-input-group').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
        }

        function setFieldError(field, message) {
            if (!field) return;

            const wrapper = field.closest('.col-12, .col-md-6') || field.parentElement;
            field.classList.add('is-invalid');
            field.closest('.sr-budget-input-group')?.classList.add('is-invalid');

            let err = wrapper.querySelector('.sr-field-error');

            if (!err) {
                err = document.createElement('div');
                err.className = 'sr-field-error';
                wrapper.appendChild(err);
            }

            err.textContent = message;
        }

        // Clear a field error as soon as the user edits it
        form.querySelectorAll('.form-control, .form-select').forEach(function(field) {
            ['input', 'change'].forEach(function(evt) {
                field.addEventListener(evt, function() {
                    field.classList.remove('is-invalid');
                    field.closest('.sr-budget-input-group')?.classList.remove('is-invalid');

                    const err = field.closest('.col-12, .col-md-6')?.querySelector(
                        '.sr-field-error'
                    );

                    if (err) err.remove();
                });
            });
        });

        function validateForm(step) {
            clearFieldErrors();

            let valid = true;
            let firstInvalid = null;

            if (step === 1 || step === 3) {
                if (servicesLoading || servicesError || !serviceSelect.value) {
                    setFieldError(
                        serviceSelect,
                        servicesError ? messages.servicesError : messages.selectService
                    );

                    valid = false;
                    firstInvalid = serviceSelect;
                }

                const title = document.getElementById('sr_title');

                if (!title.value.trim()) {
                    setFieldError(title, messages.titleRequired);
                    valid = false;
                    firstInvalid = firstInvalid || title;
                }
            }

            if (step === 2 || step === 3) {
                const description = document.getElementById('sr_description');

                if (!description.value.trim()) {
                    setFieldError(description, messages.descriptionRequired);
                    valid = false;
                    firstInvalid = firstInvalid || description;
                } else if (description.value.trim().length < 10) {
                    setFieldError(description, messages.descriptionMin);
                    valid = false;
                    firstInvalid = firstInvalid || description;
                }

                const budget = document.getElementById('sr_budget');

                if (budget.value !== '' && (isNaN(budget.value) || Number(budget.value) < 0)) {
                    setFieldError(budget, messages.budgetInvalid);
                    valid = false;
                    firstInvalid = firstInvalid || budget;
                }
            }

            if (firstInvalid) {
                firstInvalid.scrollIntoView({
                    behavior: 'smooth',
                    block: 'center'
                });

                firstInvalid.focus({
                    preventScroll: true
                });
            }

            return valid;
        }

        function showServerErrors(errors) {
            if (!errors || typeof errors !== 'object') return;

            let firstErrorStep = null;
            Object.keys(errors).forEach(function(name) {
                const msgs = Array.isArray(errors[name]) ? errors[name] : [errors[name]];

                const message = msgs.find(function(item) {
                    return typeof item === 'string' && item.length > 0;
                }) || msgs.find(function(item) {
                    return item && typeof item.message === 'string';
                });

                if (!message) return;

                let fieldName = name;
                let field = form.querySelector('[name="' + fieldName + '"]');

                if (!field && fieldName.indexOf('.') !== -1) {
                    const base = fieldName.split('.')[0];
                    field = form.querySelector('[name="' + base + '[]"]');
                    fieldName = base + '[]';
                }

                setFieldError(field, typeof message === 'string' ? message : message.message);
                const fieldPanel = field?.closest('[data-step-panel]');
                const fieldStep = fieldPanel ? Number(fieldPanel.dataset.stepPanel) : null;
                if (fieldStep && (!firstErrorStep || fieldStep < firstErrorStep)) {
                    firstErrorStep = fieldStep;
                }
            });
            if (firstErrorStep) setActiveStep(firstErrorStep);
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset alert
            alertBox.classList.add('d-none');
            alertBox.className = 'alert d-none';
            alertBox.innerHTML = '';

            // Inline validation instead of browser popups
            if (!validateForm(3)) return;

            // Disable button & show spinner
            submitBtn.disabled = true;

            if (submitSpinner) submitSpinner.classList.remove('d-none');

            const formData = new FormData(form);

            fetch(form.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                            ?.getAttribute('content') || ''
                    }
                })
                .then(async response => {
                    const data = await response.json().catch(function() {
                        return {};
                    });

                    if (response.ok && data.success) {
                        if (data.redirect_url) {
                            window.location.assign(data.redirect_url);
                            return;
                        }
                        const successMsg = data.message || messages.successMsg;
                        alertBox.className = 'alert alert-success d-block mb-3';
                        alertBox.textContent = successMsg;
                        form.reset();
                        setActiveStep(1);
                        loadServices();
                        alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    } else if (response.status === 422) {
                        const validationErrors = data.errors || data.data;

                        if (!validationErrors || typeof validationErrors !== 'object') {
                            throw new Error(data.message || messages.submitError);
                        }

                        clearFieldErrors();
                        showServerErrors(validationErrors);

                        throw {
                            silent: true
                        };
                    } else {
                        throw new Error(data.message || messages.submitError);
                    }
                })
                .catch(error => {
                    if (error && error.silent) return;

                    alertBox.className = 'alert alert-danger d-block mb-3';
                    alertBox.innerHTML = '<i class="fa fa-exclamation-triangle me-1"></i> ' +
                        (error.message || messages.connectionError);
                })
                .finally(() => {
                    submitBtn.disabled = false;

                    if (submitSpinner) submitSpinner.classList.add('d-none');
                });
        });
    });
</script>

<style>
    .custom-dd {
        position: relative;
    }

    .custom-dd-menu {
        position: absolute;
        top: 100%;
        left: 0;
        right: 0;
        z-index: 2000;
        background: #fff;
        border: 1px solid #ced4da;
        border-radius: 10px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        max-height: 240px;
        overflow-y: auto;
    }

    .custom-dd-menu a {
        display: block;
        padding: 10px 14px;
        color: #333;
        text-decoration: none;
        font-size: 0.95rem;
    }

    .custom-dd-menu a:hover {
        background: #f1f5f9;
    }
</style>

<script>
    function toggleDD(id) {
        var menu = document.getElementById(id + '_menu');

        if (!menu) return;

        var open = menu.style.display === 'block';

        // close all
        document.querySelectorAll('.custom-dd-menu').forEach(function(m) {
            m.style.display = 'none';
        });

        if (!open) menu.style.display = 'block';
    }

    function pickDD(ddId, inputId, value, text) {
        var input = document.getElementById(inputId);

        if (input) input.value = value;

        var label = document.getElementById(ddId + '_label');

        if (label) label.textContent = text;

        var menu = document.getElementById(ddId + '_menu');

        if (menu) menu.style.display = 'none';
    }

    document.addEventListener('click', function(e) {
        if (!e.target.closest('.custom-dd')) {
            document.querySelectorAll('.custom-dd-menu').forEach(function(m) {
                m.style.display = 'none';
            });
        }
    });
</script>
