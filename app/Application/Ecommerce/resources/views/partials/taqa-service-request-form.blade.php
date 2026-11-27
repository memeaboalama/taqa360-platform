

<style>
    .taqa-request-page {
        min-height: 70vh;
        padding: 24px 0 42px;
        background: #f4f7fa;
    }

    .taqa-request-page > .container {
        max-width: 1120px;
    }

    .taqa-request-heading {
        margin-bottom: 18px;
    }

    .taqa-request-heading h1 {
        margin: 0;
        color: #1a3045;
        font-size: 22px;
        font-weight: 800;
        line-height: 1.5;
    }

    .taqa-request-heading p {
        margin: 4px 0 0;
        color: #64788b;
        font-size: 13px;
        line-height: 1.7;
    }

    .taqa-request-layout {
        display: grid;
        grid-template-columns: minmax(0, 1fr) 300px;
        align-items: start;
        gap: 18px;
    }

    .taqa-request-card {
        overflow: hidden;
        border: 1px solid #dde6ee;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 6px 25px rgba(33, 61, 89, .06);
    }

    .taqa-request-steps {
        position: relative;
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        padding: 12px 18px 8px;
        border-bottom: 1px solid #dde6ee;
        background: #fcfdff;
    }

    .taqa-request-steps::before {
        position: absolute;
        inset-inline: 12%;
        top: 23px;
        height: 1px;
        background: #d4dfe9;
        content: "";
    }

    .taqa-request-step {
        position: relative;
        z-index: 1;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: flex-start;
        gap: 4px;
        min-width: 0;
        padding: 0 6px 4px;
        border: 0;
        background: transparent;
        color: #8395a6;
        font-size: 12px;
        line-height: 1.5;
        font-weight: 700;
        text-align: center;
    }

    .taqa-request-step:disabled {
        cursor: default;
    }

    .taqa-request-step-number {
        position: relative;
        z-index: 1;
        display: grid;
        flex: 0 0 23px;
        place-items: center;
        width: 23px;
        height: 23px;
        border: 1px solid #d4dfe9;
        border-radius: 50%;
        background: #fcfdff;
        color: #64788b;
        font-size: 12px;
    }

    .taqa-request-step.is-active {
        color: #306faa;
        font-weight: 800;
    }

    .taqa-request-step.is-active .taqa-request-step-number {
        border-color: #306faa;
        background: #306faa;
        color: #fff;
    }

    .taqa-request-step.is-complete .taqa-request-step-number {
        border-color: #c9e2d3;
        background: #eaf5ef;
        color: #28765e;
    }

    .taqa-request-form-body {
        padding: 20px;
    }

    .taqa-request-panel[hidden] {
        display: none !important;
    }

    .taqa-request-panel-heading {
        margin-bottom: 18px;
        text-align: start;
    }

    .taqa-request-panel-heading h2 {
        margin: 0;
        color: #1a3045;
        font-size: 20px;
        font-weight: 800;
        line-height: 1.55;
    }

    .taqa-request-panel-heading p {
        margin: 4px 0 0;
        color: #64788b;
        font-size: 12px;
        line-height: 1.7;
    }

    .taqa-request-field {
        margin-bottom: 14px;
    }

    .taqa-request-field label {
        display: block;
        margin-bottom: 5px;
        color: #35526e;
        font-size: 13px;
        font-weight: 700;
    }

    .taqa-request-field .form-control,
    .taqa-request-field .form-select {
        min-height: 40px;
        border-color: #d4dfe9;
        border-radius: 7px;
        color: #1a3045;
        font-size: 13px;
        box-shadow: none;
    }

    .taqa-request-field textarea.form-control {
        min-height: 76px;
        padding-top: 9px;
        resize: vertical;
    }

    .taqa-request-field .form-control:focus,
    .taqa-request-field .form-select:focus {
        border-color: #306faa;
        box-shadow: 0 0 0 3px rgba(48, 111, 170, .13);
    }

    .taqa-request-help {
        display: block;
        margin-top: 5px;
        color: #64788b;
        font-size: 11px;
        line-height: 1.7;
    }

    .taqa-request-service-summary {
        display: flex;
        align-items: center;
        gap: 12px;
        margin: 12px 0 18px;
        padding: 13px;
        border: 0;
        border-radius: 9px;
        background: #edf4fa;
    }

    .taqa-request-service-icon {
        display: grid;
        flex: 0 0 34px;
        place-items: center;
        width: 34px;
        height: 34px;
        border-radius: 8px;
        background: #e5f0f8;
        color: #306faa;
        font-size: 15px;
    }

    .taqa-request-service-summary strong {
        display: block;
        color: #193449;
        font-size: 13px;
    }

    .taqa-request-service-summary p {
        margin: 3px 0 0;
        color: #64788b;
        font-size: 11px;
        line-height: 1.7;
        white-space: pre-line;
    }

    .taqa-request-service-summary {
        align-items: flex-start;
    }

    .taqa-request-service-summary > div {
        min-width: 0;
        flex: 1;
    }

    .taqa-request-service-summary .taqa-request-summary-section {
        margin-top: 10px;
    }

    .taqa-request-summary-section h3 {
        margin: 0;
        color: #35526e;
        font-size: 13px;
        font-weight: 700;
    }

    .taqa-request-service-summary .taqa-request-summary-section p {
        font-size: 13px;
        overflow-wrap: anywhere;
    }

    .taqa-request-summary-section ul {
        margin: 5px 0 0;
        padding-inline-start: 20px;
        color: #64788b;
        font-size: 13px;
        line-height: 1.8;
        overflow-wrap: anywhere;
    }

    .taqa-request-aside-row[hidden],
    .taqa-request-service-summary[hidden],
    [data-taqa-service-pricing-note][hidden] { display: none !important; }

    .taqa-request-fields-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 21px 18px;
    }

    .taqa-request-fields-grid > .taqa-request-field {
        min-width: 0;
        margin-bottom: 0;
    }

    .taqa-request-field-wide {
        grid-column: 1 / -1;
    }

    .taqa-request-section {
        margin-top: 25px;
        padding-top: 24px;
        border-top: 1px solid #dde6ee;
    }

    .taqa-request-section h3 {
        margin: 0 0 17px;
        color: #1a3045;
        font-size: 17px;
        font-weight: 700;
    }

    .taqa-request-section > .taqa-request-fields-grid + .taqa-request-fields-grid,
    .taqa-request-section > .taqa-request-fields-grid + .taqa-request-field {
        margin-top: 21px;
    }

    .taqa-request-field label.taqa-request-flexible-choice {
        display: flex;
        align-items: flex-start;
        gap: 8px;
        width: fit-content;
        margin-bottom: 21px;
        padding: 9px 13px;
        border: 1px solid #d4dfe9;
        border-radius: 8px;
        background: #fff;
        cursor: pointer;
    }

    .taqa-request-flexible-choice:has(input:checked) {
        border-color: #9fc1dc;
        background: #edf4fa;
        color: #306faa;
    }

    .taqa-request-flexible-choice input {
        flex-shrink: 0;
        margin-top: 5px;
        accent-color: #306faa;
    }

    [data-taqa-panel="2"] .taqa-request-field > label {
        font-size: 14px;
    }

    [data-taqa-panel="2"] .taqa-request-help {
        font-size: 12px;
    }

    .taqa-request-map-toolbar,
    .taqa-request-map-instructions {
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 8px 12px;
    }

    .taqa-request-map-toolbar > label {
        margin-bottom: 0;
    }

    .taqa-request-map-instructions {
        margin: 8px 0 13px;
    }

    .taqa-request-map-instructions p,
    .taqa-request-map-instructions .taqa-request-map-status {
        margin: 0;
    }

    .taqa-request-map-details summary {
        width: fit-content;
        color: #306faa;
        cursor: pointer;
    }

    .taqa-request-section[data-taqa-field="attachments"] > label {
        margin-bottom: 17px;
        font-size: 17px;
    }

    button.taqa-request-upload {
        display: block;
        width: 100%;
        color: #35526e;
        font: inherit;
        cursor: pointer;
    }

    button.taqa-request-upload:hover,
    button.taqa-request-upload:focus-visible {
        border-color: #306faa;
        background: #edf4fa;
        outline: 2px solid #306faa;
        outline-offset: 3px;
    }

    [data-taqa-selected-files],
    [data-taqa-review-files] {
        padding: 0;
        list-style: none;
        text-align: start;
        overflow-wrap: anywhere;
    }

    [data-taqa-selected-files] li,
    [data-taqa-review-files] li {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-top: 8px;
        padding: 10px 12px;
        border: 1px solid #dfe5ec;
        border-radius: 8px;
        background: #fff;
    }

    .taqa-request-file-name {
        min-width: 0;
        overflow-wrap: anywhere;
    }

    .taqa-request-file-preview {
        flex-shrink: 0;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        border: 1px solid #c9ddeb;
        border-radius: 8px;
        background: #edf4fa;
        color: #306faa;
    }

    .taqa-request-preview-dialog {
        width: min(900px, calc(100% - 32px));
        max-height: 90vh;
        padding: 20px;
        border: 1px solid #d4dfe9;
        border-radius: 12px;
        color: #1a3045;
    }

    .taqa-request-preview-dialog::backdrop {
        background: rgba(15, 30, 45, .6);
    }

    .taqa-request-preview-heading {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 16px;
        margin-bottom: 16px;
    }

    .taqa-request-preview-heading h2 {
        margin: 0;
        font-size: 16px;
        overflow-wrap: anywhere;
    }

    .taqa-request-preview-body img {
        display: block;
        max-width: 100%;
        max-height: 65vh;
        margin: auto;
        object-fit: contain;
    }

    .taqa-request-preview-body iframe {
        width: 100%;
        height: 65vh;
        border: 0;
    }

    @media (max-width: 700px) {
        .taqa-request-fields-grid {
            grid-template-columns: minmax(0, 1fr);
            gap: 18px;
        }

        .taqa-request-section {
            margin-top: 22px;
            padding-top: 21px;
        }
    }

    .taqa-request-urgency {
        display: flex;
        flex-wrap: wrap;
        gap: 9px;
    }

    .taqa-request-urgency label {
        flex: 1 1 120px;
        margin: 0;
        cursor: pointer;
    }

    .taqa-request-urgency input {
        position: absolute;
        opacity: 0;
        pointer-events: none;
    }

    .taqa-request-urgency span {
        display: flex;
        min-height: 44px;
        align-items: center;
        justify-content: center;
        gap: 7px;
        padding: 9px;
        border: 1px solid #dfe5ec;
        border-radius: 9px;
        background: #fff;
        color: #51677a;
        font-size: 12px;
        transition: .18s ease;
    }

    .taqa-request-urgency input:checked + span {
        border-color: #347bb2;
        background: #edf5fb;
        color: #28618e;
        box-shadow: 0 0 0 1px #347bb2 inset;
    }

    .taqa-request-urgency input:focus-visible + span {
        outline: 3px solid rgba(52, 123, 178, .25);
        outline-offset: 2px;
    }

    .taqa-request-review {
        margin: 0;
        border: 1px solid #e3eaf0;
        border-radius: 12px;
        background: #fbfcfd;
    }

    .taqa-request-review > div {
        display: grid;
        grid-template-columns: minmax(110px, .7fr) minmax(0, 1.3fr);
        gap: 14px;
        padding: 12px 14px;
        border-bottom: 1px solid #edf1f4;
    }

    .taqa-request-review > div:last-child {
        border-bottom: 0;
    }

    .taqa-request-review dt {
        color: #7b8794;
        font-size: 12px;
        font-weight: 600;
    }

    .taqa-request-review dd {
        margin: 0;
        color: #34495b;
        font-size: 13px;
        font-weight: 700;
        overflow-wrap: anywhere;
        white-space: pre-line;
    }

    .taqa-request-upload {
        padding: 17px;
        border: 1px dashed #b9ccdb;
        border-radius: 12px;
        background: #f8fbfd;
        text-align: center;
    }

    .taqa-request-upload i {
        display: block;
        margin-bottom: 8px;
        color: #347bb2;
        font-size: 23px;
    }

    .taqa-request-map {
        height: 300px;
        margin-top: 10px;
        border: 1px solid #dfe5ec;
        border-radius: 10px;
        overflow: hidden;
    }

    .taqa-request-map-status {
        margin-top: 7px;
        color: #718294;
        font-size: 12px;
    }

    .taqa-request-map-details {
        margin-top: 10px;
    }

    .taqa-request-service-fields[hidden],
    [data-taqa-onsite-fields][hidden],
    [data-taqa-city-other][hidden],
    [data-taqa-date-wrap][hidden],
    [data-taqa-solar-field][hidden],
    [data-taqa-urgency-field][hidden] {
        display: none !important;
    }

    .taqa-request-service-fields {
        margin-top: 14px;
        padding-top: 12px;
        border-top: 1px solid #e1e8ef;
    }

    .taqa-request-service-fields h3 {
        margin: 0 0 10px;
        color: #1a3045;
        font-size: 16px;
        font-weight: 800;
    }

    .taqa-request-review-answers {
        margin-top: 18px;
    }

    .taqa-request-review-answers h3 {
        margin: 0 0 10px;
        color: #34495b;
        font-size: 14px;
        font-weight: 800;
    }

    .taqa-request-review-files {
        margin: 8px 0 0;
        padding-inline-start: 20px;
        color: #51677a;
        font-size: 12px;
    }

    .taqa-request-form-footer {
        display: flex;
        align-items: center;
        justify-content: flex-end;
        gap: 12px;
        padding: 14px 20px;
        border-top: 1px solid #dde6ee;
        background: #fcfdff;
    }

    .taqa-request-form-footer .actions {
        display: flex;
        gap: 8px;
    }

    .taqa-request-form-footer .btn {
        min-height: 38px;
        padding: 7px 14px;
        border-radius: 7px;
        font-size: 13px;
        font-weight: 700;
    }

    .taqa-request-form-footer .taqa-btn-primary {
        border-color: #306faa;
        background: #306faa;
        color: #fff;
    }

    .taqa-request-form-footer .taqa-btn-primary:hover,
    .taqa-request-form-footer .taqa-btn-primary:focus {
        border-color: #235b8e;
        background: #235b8e;
        color: #fff;
    }

    .taqa-request-form-footer .taqa-btn-soft {
        border-color: transparent;
        background: #edf4fa;
        color: #306faa;
    }

    .taqa-request-form-footer .taqa-btn-soft:hover,
    .taqa-request-form-footer .taqa-btn-soft:focus {
        border-color: transparent;
        background: #e1edf6;
        color: #235b8e;
    }

    .taqa-request-aside {
        position: sticky;
        top: 96px;
        padding: 20px;
        border: 1px solid #dde6ee;
        border-radius: 12px;
        background: #fff;
        box-shadow: 0 6px 25px rgba(33, 61, 89, .06);
    }

    .taqa-request-aside h2 {
        margin: 0;
        color: #1a3045;
        font-size: 17px;
        font-weight: 800;
    }

    .taqa-request-aside > p {
        margin: 6px 0 14px;
        color: #64788b;
        font-size: 12px;
        line-height: 1.7;
    }

    .taqa-request-aside-row {
        display: flex;
        justify-content: space-between;
        gap: 12px;
        padding: 11px 0;
        border-top: 1px solid #dde6ee;
        font-size: 12px;
    }

    .taqa-request-aside-row span {
        color: #64788b;
    }

    .taqa-request-aside-row strong {
        max-width: 60%;
        overflow-wrap: anywhere;
        color: #1a3045;
        text-align: end;
    }

    .taqa-request-alert {
        margin-bottom: 18px;
    }

    .taqa-request-error {
        display: block;
        margin-top: 5px;
        color: #b42318;
        font-size: 12px;
        font-weight: 600;
    }

    .taqa-request-field .is-invalid {
        border-color: #b42318 !important;
    }

    @media (max-width: 900px) {
        .taqa-request-layout {
            grid-template-columns: minmax(0, 1fr);
        }

        .taqa-request-aside {
            position: static;
        }
    }

    @media (max-width: 575px) {
        .taqa-request-page {
            padding: 18px 0 28px;
        }

        .taqa-request-heading h1 {
            font-size: 20px;
        }

        .taqa-request-heading p {
            font-size: 12px;
        }

        .taqa-request-steps {
            padding: 10px 5px 6px;
        }

        .taqa-request-steps::before {
            top: 21px;
        }

        .taqa-request-step {
            gap: 3px;
            padding: 0 2px 4px;
            font-size: 10px;
            text-align: center;
        }

        .taqa-request-form-body {
            padding: 15px;
        }

        .taqa-request-form-footer {
            padding: 12px;
        }

        .taqa-request-form-footer .btn {
            padding-inline: 11px;
            font-size: 12px;
        }

        .taqa-request-review > div {
            grid-template-columns: 1fr;
            gap: 4px;
        }
    }
</style>

<main class="taqa-request-page" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'rtl' : 'ltr' }}">
    <div class="container">
        <header class="taqa-request-heading">
            <h1>@lang('ecommerce::ecommerce.sr_page_new_request')</h1>
            <p>@lang('ecommerce::ecommerce.sr_page_new_request_help')</p>
        </header>

        <div class="taqa-request-layout">
            <section class="taqa-request-card">
                <div class="taqa-request-steps" role="group" aria-label="@lang('ecommerce::ecommerce.sr_page_intro')">
                    @foreach ([
                        1 => 'ecommerce::ecommerce.sr_page_step_basics',
                        2 => 'ecommerce::ecommerce.sr_page_step_details',
                        3 => 'ecommerce::ecommerce.sr_page_step_files',
                    ] as $step => $label)
                        <button type="button" class="taqa-request-step {{ $step === 1 ? 'is-active' : '' }}"
                            data-taqa-step="{{ $step }}" {{ $step === 1 ? 'aria-current=step' : 'disabled' }}>
                            <span class="taqa-request-step-number">{{ $step }}</span>
                            <span>@lang($label)</span>
                        </button>
                    @endforeach
                </div>

                <form id="taqaServiceRequestForm" action="{{ route('service-requests.store', [], false) }}"
                    method="POST" enctype="multipart/form-data" novalidate>
                    @csrf
                    <div class="taqa-request-form-body">
                        <div class="alert d-none taqa-request-alert" data-taqa-alert role="alert"></div>
                        <div class="alert alert-danger d-none taqa-request-alert" data-taqa-service-error role="alert"></div>

                        <section class="taqa-request-panel" data-taqa-panel="1">
                            <div class="taqa-request-panel-heading">
                                <div>
                                    <h2>@lang('ecommerce::ecommerce.sr_form_service_question')</h2>
                                    <p>@lang('ecommerce::ecommerce.sr_form_need_details')</p>
                                </div>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="service_id">
                                <label for="taqa_service_id">@lang('ecommerce::ecommerce.sr_form_service_label') <span class="text-danger">*</span></label>
                                <select class="form-select" id="taqa_service_id" name="service_id"
                                    data-selected-service="{{ request('service') }}" required disabled>
                                    <option value="">@lang('ecommerce::ecommerce.loading')</option>
                                </select>
                                <span class="taqa-request-help">@lang('ecommerce::ecommerce.sr_form_service_help')</span>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            <div class="taqa-request-service-summary" data-taqa-service-summary hidden>
                                <span class="taqa-request-service-icon" aria-hidden="true"><i class="fa fa-sun-o"></i></span>
                                <div>
                                    <strong data-taqa-service-name></strong>
                                    <div class="taqa-request-summary-section" data-taqa-service-description-wrap hidden>
                                        <p data-taqa-service-description></p>
                                    </div>
                                    <div class="taqa-request-summary-section" data-taqa-service-scope-wrap hidden>
                                        <h3>@lang('ecommerce::ecommerce.sr_form_service_scope')</h3>
                                        <p data-taqa-service-scope></p>
                                    </div>
                                    @foreach(['deliverables' => 'sr_service_deliverables', 'exclusions' => 'sr_service_exclusions', 'requirements' => 'sr_service_requirements'] as $field => $label)
                                        <div class="taqa-request-summary-section" data-taqa-service-list-wrap="{{ $field }}" hidden>
                                            <h3>@lang('ecommerce::ecommerce.' . $label)</h3>
                                            <ul data-taqa-service-list="{{ $field }}"></ul>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="description">
                                <label for="taqa_description">@lang('ecommerce::ecommerce.sr_form_description_label') <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="taqa_description" name="description" rows="5"
                                    required placeholder="@lang('ecommerce::ecommerce.sr_description_placeholder')"></textarea>
                                <span class="taqa-request-help">@lang('ecommerce::ecommerce.sr_form_description_help')</span>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="title">
                                <label for="taqa_title">@lang('ecommerce::ecommerce.sr_request_title')</label>
                                <input class="form-control" type="text" id="taqa_title" name="title" maxlength="255"
                                    placeholder="@lang('ecommerce::ecommerce.sr_title_placeholder')">
                                <span class="taqa-request-help">@lang('ecommerce::ecommerce.sr_form_title_help')</span>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            <div class="row">
                                <div class="col-md-6">
                                    <div class="taqa-request-field" data-taqa-field="budget">
                                        <label for="taqa_budget">@lang('ecommerce::ecommerce.sr_budget_label')</label>
                                        <input class="form-control" type="number" id="taqa_budget" name="budget"
                                            min="0" step="0.01" placeholder="@lang('ecommerce::ecommerce.sr_budget_placeholder')">
                                        <small class="taqa-request-error" data-taqa-error hidden></small>
                                    </div>
                                </div>
                                <div class="col-md-6" data-taqa-solar-field hidden>
                                    <div class="taqa-request-field" data-taqa-field="solar_system_type">
                                        <label for="taqa_solar_system_type">@lang('ecommerce::ecommerce.sr_solar_system_type')</label>
                                        <select class="form-select" id="taqa_solar_system_type" name="solar_system_type">
                                            <option value="">@lang('ecommerce::ecommerce.sr_select_service')</option>
                                            <option value="off-grid">@lang('ecommerce::ecommerce.solar_system_off_grid')</option>
                                            <option value="on-grid">@lang('ecommerce::ecommerce.solar_system_on_grid')</option>
                                            <option value="hybrid">@lang('ecommerce::ecommerce.solar_system_hybrid')</option>
                                            <option value="not_sure">@lang('ecommerce::ecommerce.solar_system_not_sure')</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="taqa-request-service-fields" data-taqa-service-fields hidden>
                                <h3>@lang('ecommerce::ecommerce.sr_form_questions')</h3>
                                <div class="row" data-taqa-service-fields-list></div>
                            </div>

                        </section>

                        <section class="taqa-request-panel" data-taqa-panel="2" hidden>
                            <div class="taqa-request-panel-heading">
                                <div>
                                    <h2>@lang('ecommerce::ecommerce.sr_form_location_heading')</h2>
                                    <p>@lang('ecommerce::ecommerce.sr_form_location_help')</p>
                                </div>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="delivery_mode">
                                <label for="taqa_delivery_mode">@lang('ecommerce::ecommerce.sr_form_delivery_mode') <span class="text-danger">*</span></label>
                                <select class="form-select" id="taqa_delivery_mode" name="delivery_mode" required>
                                    <option value="onsite">@lang('ecommerce::ecommerce.sr_form_onsite')</option>
                                    <option value="remote">@lang('ecommerce::ecommerce.sr_form_remote')</option>
                                </select>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            <div class="taqa-request-fields-grid" data-taqa-onsite-fields>
                                <div class="taqa-request-field" data-taqa-field="property_type">
                                    <label for="taqa_property_type">@lang('ecommerce::ecommerce.sr_form_property') <span class="text-danger">*</span></label>
                                    <select class="form-select" id="taqa_property_type" name="property_type">
                                        <option value="">@lang('ecommerce::ecommerce.sr_select_service')</option>
                                        <option value="home">@lang('ecommerce::ecommerce.sr_form_home')</option>
                                        <option value="commercial">@lang('ecommerce::ecommerce.sr_form_commercial')</option>
                                        <option value="industrial">@lang('ecommerce::ecommerce.sr_form_industrial')</option>
                                        <option value="farm">@lang('ecommerce::ecommerce.sr_form_farm')</option>
                                        <option value="public">@lang('ecommerce::ecommerce.sr_form_public')</option>
                                        <option value="other">@lang('ecommerce::ecommerce.sr_form_other')</option>
                                    </select>
                                    <small class="taqa-request-error" data-taqa-error hidden></small>
                                </div>

                                <div class="taqa-request-field" data-taqa-field="site_city">
                                    <label for="taqa_site_city">@lang('ecommerce::ecommerce.city') <span class="text-danger">*</span></label>
                                    <select class="form-select" id="taqa_site_city" name="site_city">
                                        <option value="">@lang('ecommerce::ecommerce.sr_select_service')</option>
                                        <option value="riyadh">@lang('ecommerce::ecommerce.sr_form_city_riyadh')</option>
                                        <option value="jeddah">@lang('ecommerce::ecommerce.sr_form_city_jeddah')</option>
                                        <option value="dammam">@lang('ecommerce::ecommerce.sr_form_city_dammam')</option>
                                        <option value="other">@lang('ecommerce::ecommerce.sr_form_city_other')</option>
                                    </select>
                                    <small class="taqa-request-error" data-taqa-error hidden></small>
                                </div>

                                <div class="taqa-request-field taqa-request-field-wide" data-taqa-field="site_city_other" data-taqa-city-other hidden>
                                    <label for="taqa_site_city_other">@lang('ecommerce::ecommerce.sr_form_city_other_name') <span class="text-danger">*</span></label>
                                    <input class="form-control" type="text" id="taqa_site_city_other" name="site_city_other" maxlength="100">
                                    <small class="taqa-request-error" data-taqa-error hidden></small>
                                </div>

                                <div class="taqa-request-field taqa-request-field-wide" data-taqa-field="service_address">
                                    <label for="taqa_service_address">@lang('ecommerce::ecommerce.sr_form_address') <span class="text-danger">*</span></label>
                                    <textarea class="form-control" id="taqa_service_address" name="service_address" rows="3"
                                        placeholder="@lang('ecommerce::ecommerce.address_placeholder')"></textarea>
                                    <small class="taqa-request-error" data-taqa-error hidden></small>
                                </div>

                                <div class="taqa-request-field taqa-request-field-wide" data-taqa-field="location">
                                    <div class="taqa-request-map-toolbar">
                                    <label>@lang('ecommerce::ecommerce.location_on_map') <span class="text-muted fw-normal">@lang('ecommerce::ecommerce.sr_form_optional')</span></label>
                                        <button class="btn btn-light border btn-sm" type="button" data-taqa-use-location>
                                            @lang('ecommerce::ecommerce.use_current_location')
                                        </button>
                                    </div>
                                    <div class="taqa-request-map" data-taqa-map role="application"
                                        aria-label="@lang('ecommerce::ecommerce.location_on_map')"></div>
                                    <div class="taqa-request-map-instructions">
                                        <p class="taqa-request-help">@lang('ecommerce::ecommerce.address_map_help')</p>
                                        <span class="taqa-request-map-status" data-taqa-map-status role="status" aria-live="polite">
                                            @lang('ecommerce::ecommerce.no_location_selected')
                                        </span>
                                    </div>
                                    <details class="taqa-request-map-details">
                                        <summary class="taqa-request-help">@lang('ecommerce::ecommerce.sr_form_coordinates')</summary>
                                        <div class="row mt-2">
                                            <div class="col-md-6 taqa-request-field" data-taqa-field="latitude">
                                                <label for="taqa_latitude">@lang('ecommerce::ecommerce.sr_form_latitude')</label>
                                                <input class="form-control" type="number" step="any" id="taqa_latitude" name="latitude" min="-90" max="90">
                                                <small class="taqa-request-error" data-taqa-error hidden></small>
                                            </div>
                                            <div class="col-md-6 taqa-request-field" data-taqa-field="longitude">
                                                <label for="taqa_longitude">@lang('ecommerce::ecommerce.sr_form_longitude')</label>
                                                <input class="form-control" type="number" step="any" id="taqa_longitude" name="longitude" min="-180" max="180">
                                                <small class="taqa-request-error" data-taqa-error hidden></small>
                                            </div>
                                        </div>
                                    </details>
                                </div>

                                <div class="taqa-request-field taqa-request-field-wide" data-taqa-field="access_notes">
                                    <label for="taqa_access_notes">@lang('ecommerce::ecommerce.sr_form_access')</label>
                                    <input class="form-control" type="text" id="taqa_access_notes" name="access_notes"
                                        placeholder="@lang('ecommerce::ecommerce.sr_form_access_help')">
                                </div>
                            </div>

                            <p class="alert alert-info" data-taqa-remote-note hidden>
                                @lang('ecommerce::ecommerce.sr_form_remote_location_help')
                            </p>

                            <div class="taqa-request-section">
                            <div class="taqa-request-field">
                                <h3>@lang('ecommerce::ecommerce.sr_form_schedule')</h3>
                                <label class="taqa-request-flexible-choice">
                                    <input type="hidden" name="is_flexible" value="1">
                                    <input type="checkbox" data-taqa-flexible checked>
                                    <span class="mb-0">@lang('ecommerce::ecommerce.sr_form_flexible')</span>
                                </label>
                            </div>

                            <div class="taqa-request-fields-grid">
                            <div class="taqa-request-field" data-taqa-field="preferred_date" data-taqa-date-wrap hidden>
                                <label for="taqa_preferred_date">@lang('ecommerce::ecommerce.sr_form_preferred_date') <span class="text-danger">*</span></label>
                                <input class="form-control" type="date" id="taqa_preferred_date" name="preferred_date">
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="preferred_period">
                                <label for="taqa_preferred_period">@lang('ecommerce::ecommerce.sr_form_preferred_period')</label>
                                <select class="form-select" id="taqa_preferred_period" name="preferred_period">
                                    <option value="flexible">@lang('ecommerce::ecommerce.sr_form_period_flexible')</option>
                                    <option value="morning">@lang('ecommerce::ecommerce.sr_form_period_morning')</option>
                                    <option value="evening">@lang('ecommerce::ecommerce.sr_form_period_evening')</option>
                                </select>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="urgency_level" data-taqa-urgency-field hidden>
                                <label>@lang('ecommerce::ecommerce.urgency_level')</label>
                                <div class="taqa-request-urgency">
                                    <label><input type="radio" name="urgency_level" value="normal" checked><span>@lang('ecommerce::ecommerce.urgency_normal')</span></label>
                                    <label><input type="radio" name="urgency_level" value="urgent"><span>@lang('ecommerce::ecommerce.urgency_urgent')</span></label>
                                    <label><input type="radio" name="urgency_level" value="very_urgent"><span>@lang('ecommerce::ecommerce.urgency_very_urgent')</span></label>
                                </div>
                            </div>

                            </div>

                            <div class="taqa-request-fields-grid">
                            <div class="taqa-request-field" data-taqa-field="contact_name">
                                <label for="taqa_contact_name">@lang('ecommerce::ecommerce.sr_form_contact_name') <span class="text-danger">*</span></label>
                                <input class="form-control" type="text" id="taqa_contact_name" name="contact_name"
                                    value="{{ auth()->user()?->name }}" maxlength="255" required>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            <div class="taqa-request-field" data-taqa-field="contact_phone">
                                <label for="taqa_contact_phone">@lang('ecommerce::ecommerce.sr_form_contact_phone') <span class="text-danger">*</span></label>
                                <input class="form-control" type="tel" id="taqa_contact_phone" name="contact_phone"
                                    value="{{ auth()->user()?->phone_number }}" maxlength="30" required>
                                <span class="taqa-request-help">@lang('ecommerce::ecommerce.sr_form_contact_phone_help')</span>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>

                            </div>

                            <div class="taqa-request-field" data-taqa-field="additional_notes">
                                <label for="taqa_additional_notes">@lang('ecommerce::ecommerce.additional_notes')</label>
                                <textarea class="form-control" id="taqa_additional_notes" name="additional_notes" rows="3"
                                    placeholder="@lang('ecommerce::ecommerce.sr_notes_placeholder')"></textarea>
                            </div>
                            </div>

                            <div class="taqa-request-field taqa-request-section" data-taqa-field="attachments">
                                <label for="taqa_attachments">@lang('ecommerce::ecommerce.sr_attachments_label') <span class="text-muted fw-normal">@lang('ecommerce::ecommerce.sr_form_optional')</span></label>
                                <input type="file" id="taqa_attachments" name="attachments[]" hidden
                                    multiple accept="image/jpeg,image/png,image/webp,application/pdf,.docx">
                                <button type="button" class="taqa-request-upload" data-taqa-pick-files
                                    aria-describedby="taqa_upload_help">
                                    <i class="fa fa-cloud-upload" aria-hidden="true"></i>
                                    <span>@lang('ecommerce::ecommerce.sr_form_pick_files')</span>
                                    <small class="taqa-request-help" id="taqa_upload_help">@lang('ecommerce::ecommerce.sr_form_files_limit')</small>
                                </button>
                                <ul class="taqa-request-review-files" data-taqa-selected-files></ul>
                                <small class="taqa-request-error" data-taqa-error hidden></small>
                            </div>
                        </section>

                        <section class="taqa-request-panel" data-taqa-panel="3" hidden>
                            <div class="taqa-request-panel-heading">
                                <div>
                                    <h2>@lang('ecommerce::ecommerce.sr_page_step_files')</h2>
                                    <p>@lang('ecommerce::ecommerce.sr_form_review_help')</p>
                                </div>
                            </div>

                            <dl class="taqa-request-review mb-4">
                                <div><dt>@lang('ecommerce::ecommerce.service_type')</dt><dd data-taqa-review="service">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_request_title')</dt><dd data-taqa-review="title">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_description')</dt><dd data-taqa-review="description">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_form_delivery_mode')</dt><dd data-taqa-review="mode">—</dd></div>
                                <div data-taqa-review-onsite><dt>@lang('ecommerce::ecommerce.sr_form_property')</dt><dd data-taqa-review="property">—</dd></div>
                                <div data-taqa-review-onsite><dt>@lang('ecommerce::ecommerce.city')</dt><dd data-taqa-review="city">—</dd></div>
                                <div data-taqa-review-onsite><dt>@lang('ecommerce::ecommerce.sr_form_address')</dt><dd data-taqa-review="address">—</dd></div>
                                <div data-taqa-review-onsite><dt>@lang('ecommerce::ecommerce.location_on_map')</dt><dd data-taqa-review="coordinates">—</dd></div>
                                <div data-taqa-review-onsite><dt>@lang('ecommerce::ecommerce.sr_form_access')</dt><dd data-taqa-review="access">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_form_preferred_date')</dt><dd data-taqa-review="date">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_form_preferred_period')</dt><dd data-taqa-review="period">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_form_contact_name')</dt><dd data-taqa-review="contact_name">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_form_contact_phone')</dt><dd data-taqa-review="contact_phone">—</dd></div>
                                <div data-taqa-review-solar><dt>@lang('ecommerce::ecommerce.sr_solar_system_type')</dt><dd data-taqa-review="system">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.sr_budget_label')</dt><dd data-taqa-review="budget">—</dd></div>
                                <div data-taqa-review-urgency><dt>@lang('ecommerce::ecommerce.urgency_level')</dt><dd data-taqa-review="urgency">—</dd></div>
                                <div><dt>@lang('ecommerce::ecommerce.additional_notes')</dt><dd data-taqa-review="notes">—</dd></div>
                            </dl>
                            <div class="taqa-request-review-answers" data-taqa-review-answers hidden>
                                <h3>@lang('ecommerce::ecommerce.sr_form_questions')</h3>
                                <dl class="taqa-request-review" data-taqa-review-answer-list></dl>
                            </div>

                            <section class="taqa-request-review-answers">
                                <h3>@lang('ecommerce::ecommerce.sr_attachments_label')</h3>
                                <ul class="taqa-request-review-files" data-taqa-review-files></ul>
                                <p class="taqa-request-help" data-taqa-no-files>@lang('ecommerce::ecommerce.sr_form_no_files')</p>
                            </section>
                            <p class="alert alert-info mt-3">@lang('ecommerce::ecommerce.sr_form_review_notice')</p>
                        </section>
                    </div>

                    <footer class="taqa-request-form-footer">
                        <div class="actions">
                            <button type="button" class="btn taqa-btn-soft" data-taqa-save-draft>
                                <i class="fa fa-floppy-o" aria-hidden="true"></i>
                                @lang('ecommerce::ecommerce.sr_form_save_draft')
                            </button>
                            <button type="button" class="btn btn-light border" data-taqa-previous hidden>
                                @lang('ecommerce::ecommerce.sr_page_previous')
                            </button>
                            <button type="button" class="btn taqa-btn-primary" data-taqa-next>
                                @lang('ecommerce::ecommerce.sr_page_next')
                            </button>
                            <button type="submit" class="btn taqa-btn-primary" data-taqa-submit hidden>
                                <span class="spinner-border spinner-border-sm me-2 d-none" data-taqa-spinner
                                    role="status" aria-hidden="true"></span>
                                <i class="fa fa-paper-plane me-1" aria-hidden="true"></i>
                                @lang('ecommerce::ecommerce.sr_submit')
                            </button>
                        </div>
                    </footer>
                </form>
            </section>

            <aside class="taqa-request-aside">
                <h2>@lang('ecommerce::ecommerce.sr_page_summary_title')</h2>
                <p>@lang('ecommerce::ecommerce.sr_page_summary_help')</p>
                <div class="taqa-request-aside-row">
                    <span>@lang('ecommerce::ecommerce.sr_page_summary_service')</span>
                    <strong data-taqa-summary="service">@lang('ecommerce::ecommerce.sr_select_service')</strong>
                </div>
                <div class="taqa-request-aside-row" data-taqa-service-pricing hidden>
                    <span>@lang('ecommerce::ecommerce.sr_service_pricing')</span>
                    <strong data-taqa-summary="pricing"></strong>
                </div>
                <div class="taqa-request-aside-row" data-taqa-service-duration hidden>
                    <span>@lang('ecommerce::ecommerce.sr_service_duration')</span>
                    <strong data-taqa-summary="duration"></strong>
                </div>
                <p class="taqa-request-help" data-taqa-service-pricing-note hidden>@lang('ecommerce::ecommerce.sr_service_pricing_help')</p>
                <div class="taqa-request-aside-row">
                    <span>@lang('ecommerce::ecommerce.sr_page_summary_budget')</span>
                    <strong data-taqa-summary="budget">—</strong>
                </div>
                <div class="taqa-request-aside-row">
                    <span>@lang('ecommerce::ecommerce.sr_page_summary_urgency')</span>
                    <strong data-taqa-summary="urgency">@lang('ecommerce::ecommerce.urgency_normal')</strong>
                </div>
            </aside>
        </div>
    </div>
    <dialog class="taqa-request-preview-dialog" data-taqa-preview-dialog aria-labelledby="taqa_preview_title">
        <div class="taqa-request-preview-heading">
            <h2 id="taqa_preview_title" data-taqa-preview-title></h2>
            <button type="button" class="btn btn-light border" data-taqa-close-preview
                aria-label="@lang('ecommerce::ecommerce.sr_form_close_preview')">&times;</button>
        </div>
        <div class="taqa-request-preview-body" data-taqa-preview-body></div>
        <a class="btn btn-light border mt-3" data-taqa-preview-download download>
            <i class="fa fa-download" aria-hidden="true"></i>
            @lang('ecommerce::ecommerce.sr_form_download_file')
        </a>
    </dialog>
</main>

@include('shared.maps')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.getElementById('taqaServiceRequestForm');
        if (!form) return;

        const serviceSelect = form.querySelector('[name="service_id"]');
        const modeSelect = form.querySelector('[name="delivery_mode"]');
        const onsiteFields = form.querySelector('[data-taqa-onsite-fields]');
        const citySelect = form.querySelector('[name="site_city"]');
        const cityOther = form.querySelector('[data-taqa-city-other]');
        const flexibleCheckbox = form.querySelector('[data-taqa-flexible]');
        const flexibleInput = form.querySelector('[name="is_flexible"]');
        const preferredDate = form.querySelector('[name="preferred_date"]');
        const fileInput = form.querySelector('[name="attachments[]"]');
        const pickFilesButton = form.querySelector('[data-taqa-pick-files]');
        const previewDialog = document.querySelector('[data-taqa-preview-dialog]');
        const previewBody = previewDialog.querySelector('[data-taqa-preview-body]');
        const previewDownload = previewDialog.querySelector('[data-taqa-preview-download]');
        let previewUrl = null;
        const panels = Array.from(form.querySelectorAll('[data-taqa-panel]'));
        const steps = Array.from(document.querySelectorAll('[data-taqa-step]'));
        const alertBox = form.querySelector('[data-taqa-alert]');
        const serviceError = form.querySelector('[data-taqa-service-error]');
        const nextButton = form.querySelector('[data-taqa-next]');
        const previousButton = form.querySelector('[data-taqa-previous]');
        const submitButton = form.querySelector('[data-taqa-submit]');
        const saveDraftButton = form.querySelector('[data-taqa-save-draft]');
        const spinner = form.querySelector('[data-taqa-spinner]');
        const selectedServices = new Map();
        const draftKey = 'taqa360-service-request-draft-v1';
        const cityCenters = {
            riyadh: [24.7136, 46.6753],
            jeddah: [21.5433, 39.1728],
            dammam: [26.4207, 50.0888],
            other: [24.7136, 46.6753],
        };
        let currentStep = 1;
        let servicesFailed = false;
        let requestMap = null;
        let requestMarker = null;
        let savedDraft = null;
        let storageWarning = false;

        const messages = {
            loading: @json(__('ecommerce::ecommerce.loading')),
            selectService: @json(__('ecommerce::ecommerce.sr_select_service')),
            noServices: @json(__('ecommerce::ecommerce.sr_no_services')),
            loadError: @json(__('ecommerce::ecommerce.sr_catalog_load_error')),
            descriptionRequired: @json(__('ecommerce::ecommerce.sr_description_required')),
            budgetInvalid: @json(__('ecommerce::ecommerce.sr_budget_invalid')),
            connectionError: @json(__('ecommerce::ecommerce.sr_connection_error')),
            submitError: @json(__('ecommerce::ecommerce.sr_submit_error')),
            success: @json(__('ecommerce::ecommerce.sr_success_msg')),
            modeRequired: @json(__('ecommerce::ecommerce.sr_form_mode_required')),
            propertyRequired: @json(__('ecommerce::ecommerce.sr_form_property_required')),
            cityRequired: @json(__('ecommerce::ecommerce.sr_form_city_required')),
            cityOtherRequired: @json(__('ecommerce::ecommerce.sr_form_city_other_required')),
            addressRequired: @json(__('ecommerce::ecommerce.sr_form_address_required')),
            contactRequired: @json(__('ecommerce::ecommerce.sr_form_contact_required')),
            phoneInvalid: @json(__('ecommerce::ecommerce.sr_form_phone_invalid')),
            dateInvalid: @json(__('ecommerce::ecommerce.sr_form_date_invalid')),
            coordinatesInvalid: @json(__('ecommerce::ecommerce.sr_form_coordinates_invalid')),
            answerRequired: @json(__('ecommerce::ecommerce.sr_form_answer_required')),
            answerNumberInvalid: @json(__('ecommerce::ecommerce.sr_form_answer_number_invalid')),
            fileCountError: @json(__('ecommerce::ecommerce.sr_form_file_count_error')),
            fileTypeError: @json(__('ecommerce::ecommerce.sr_form_file_type_error')),
            fileSizeError: @json(__('ecommerce::ecommerce.sr_form_file_size_error')),
            mapLoadError: @json(__('ecommerce::ecommerce.map_load_error')),
            locationUnavailable: @json(__('ecommerce::ecommerce.location_unavailable')),
            locationSelected: @json(__('ecommerce::ecommerce.location_selected')),
            noLocation: @json(__('ecommerce::ecommerce.no_location_selected')),
            storageUnavailable: @json(__('ecommerce::ecommerce.sr_form_storage_error')),
            draftSaved: @json(__('ecommerce::ecommerce.sr_form_draft_saved')),
            priceQuote: @json(__('ecommerce::ecommerce.sr_service_price_quote')),
            priceFixed: @json(__('ecommerce::ecommerce.sr_service_price_fixed')),
            priceFrom: @json(__('ecommerce::ecommerce.sr_service_price_from')),
            previewFile: @json(__('ecommerce::ecommerce.sr_form_preview_file')),
            previewDownloadHelp: @json(__('ecommerce::ecommerce.sr_form_preview_download_help')),
            notSpecified: '—',
            currency: @json(__('ecommerce::ecommerce.currency_symbol')),
            modes: {
                onsite: @json(__('ecommerce::ecommerce.sr_form_onsite')),
                remote: @json(__('ecommerce::ecommerce.sr_form_remote')),
            },
            properties: {
                home: @json(__('ecommerce::ecommerce.sr_form_home')),
                commercial: @json(__('ecommerce::ecommerce.sr_form_commercial')),
                industrial: @json(__('ecommerce::ecommerce.sr_form_industrial')),
                farm: @json(__('ecommerce::ecommerce.sr_form_farm')),
                public: @json(__('ecommerce::ecommerce.sr_form_public')),
                other: @json(__('ecommerce::ecommerce.sr_form_other')),
            },
            cities: {
                riyadh: @json(__('ecommerce::ecommerce.sr_form_city_riyadh')),
                jeddah: @json(__('ecommerce::ecommerce.sr_form_city_jeddah')),
                dammam: @json(__('ecommerce::ecommerce.sr_form_city_dammam')),
                other: @json(__('ecommerce::ecommerce.sr_form_city_other')),
            },
            periods: {
                flexible: @json(__('ecommerce::ecommerce.sr_form_period_flexible')),
                morning: @json(__('ecommerce::ecommerce.sr_form_period_morning')),
                evening: @json(__('ecommerce::ecommerce.sr_form_period_evening')),
            },
            systemTypes: {
                'off-grid': @json(__('ecommerce::ecommerce.solar_system_off_grid')),
                'on-grid': @json(__('ecommerce::ecommerce.solar_system_on_grid')),
                hybrid: @json(__('ecommerce::ecommerce.solar_system_hybrid')),
                not_sure: @json(__('ecommerce::ecommerce.solar_system_not_sure')),
            },
        };

        function localized(value) {
            if (typeof value === 'string') return value;
            if (!value || typeof value !== 'object') return '';
            const locale = @json(app()->getLocale());
            return value[locale] || value.ar || value.en || '';
        }

        try {
            const draft = localStorage.getItem(draftKey);
            savedDraft = draft ? JSON.parse(draft) : null;
        } catch (error) {
            storageWarning = true;
        }

        if (storageWarning) {
            alertBox.className = 'alert alert-warning taqa-request-alert';
            alertBox.textContent = messages.storageUnavailable;
        }

        function selectedService() {
            return selectedServices.get(serviceSelect.value) || null;
        }

        function serviceConfig(service) {
            return service && service.request_config && typeof service.request_config === 'object'
                ? service.request_config
                : {};
        }

        function setText(selector, value) {
            const element = document.querySelector(selector);
            if (element) element.textContent = value || messages.notSpecified;
        }

        function cityLabel() {
            return citySelect.value === 'other'
                ? form.querySelector('[name="site_city_other"]').value.trim()
                : (messages.cities[citySelect.value] || '');
        }

        function serviceTitle() {
            const service = selectedService();
            const city = modeSelect.value === 'onsite' ? cityLabel() : '';
            return (service ? localized(service.name) : messages.selectService) +
                (city ? ' — ' + city : '');
        }

        function ensureSuggestedTitle() {
            const title = form.querySelector('[name="title"]');
            if (!title.value.trim()) title.value = serviceTitle().slice(0, 255);
        }

        function updateServiceSummary() {
            const service = selectedService();
            const summary = form.querySelector('[data-taqa-service-summary]');
            summary.hidden = !service;
            form.querySelector('[data-taqa-service-name]').textContent = service ? localized(service.name) : '';
            const description = service ? localized(service.description) : '';
            form.querySelector('[data-taqa-service-description]').textContent = description;
            form.querySelector('[data-taqa-service-description-wrap]').hidden = !description;
            const scope = form.querySelector('[data-taqa-service-scope]');
            const scopeText = service ? localized(service.scope_of_work) : '';
            scope.textContent = scopeText;
            form.querySelector('[data-taqa-service-scope-wrap]').hidden = !scopeText;
            ['deliverables', 'exclusions', 'requirements'].forEach(function (key) {
                const value = service && service[key];
                const locale = @json(app()->getLocale());
                const lists = value && typeof value === 'object' ? value : {};
                const items = Array.isArray(value) ? value :
                    [lists[locale], lists.ar, lists.en].find((items) => Array.isArray(items) && items.length) || [];
                const list = form.querySelector('[data-taqa-service-list="' + key + '"]');
                list.replaceChildren();
                items.filter((item) => typeof item === 'string' && item.trim()).forEach(function (item) {
                    const row = document.createElement('li');
                    row.textContent = item;
                    list.appendChild(row);
                });
                form.querySelector('[data-taqa-service-list-wrap="' + key + '"]').hidden = list.children.length === 0;
            });
            const pricing = servicePricing(service);
            const duration = service ? localized(service.duration_translations) || localized(service.duration) : '';
            setText('[data-taqa-summary="pricing"]', pricing);
            setText('[data-taqa-summary="duration"]', duration);
            document.querySelector('[data-taqa-service-pricing]').hidden = !pricing;
            document.querySelector('[data-taqa-service-duration]').hidden = !duration;
            document.querySelector('[data-taqa-service-pricing-note]').hidden = !pricing;
            setText('[data-taqa-summary="service"]', service ? localized(service.name) : messages.selectService);
        }

        function servicePricing(service) {
            if (!service) return '';
            if (!service.pricing_mode || service.pricing_mode === 'quote') return messages.priceQuote;
            if (service.price === null || service.price === '' || service.price === undefined ||
                !Number.isFinite(Number(service.price)) || Number(service.price) < 0) return '';
            const amount = new Intl.NumberFormat(@json(app()->getLocale()), { maximumFractionDigits: 2 }).format(Number(service.price));
            return (service.pricing_mode === 'from' ? messages.priceFrom : messages.priceFixed) + ' ' + amount + ' ' + messages.currency;
        }

        function addServiceField(field, value) {
            const column = document.createElement('div');
            column.className = field.type === 'textarea' ? 'col-12' : 'col-md-6';

            const wrapper = document.createElement('div');
            wrapper.className = 'taqa-request-field';
            wrapper.dataset.taqaField = 'service_answers.' + field.key;

            const controlId = 'taqa_answer_' + field.key;
            const label = document.createElement('label');
            label.htmlFor = controlId;
            label.textContent = localized(field.label);
            if (field.required) {
                const required = document.createElement('span');
                required.className = 'text-danger';
                required.textContent = ' *';
                label.appendChild(required);
            }

            let control;
            if (field.type === 'select') {
                control = document.createElement('select');
                control.className = 'form-select';
                control.add(new Option(messages.selectService, ''));
                let fieldOptions = Array.isArray(field.options) ? field.options : [];
                if (!Array.isArray(field.options) && field.options && typeof field.options === 'object') {
                    const localizedOptions = field.options[@json(app()->getLocale())];
                    const fallbackOptions = [field.options.ar, field.options.en]
                        .find((options) => Array.isArray(options) && options.length) || [];
                    fieldOptions = Array.isArray(localizedOptions) && localizedOptions.length
                        ? localizedOptions
                        : fallbackOptions;
                }
                fieldOptions.forEach(function (option) {
                    const optionValue = typeof option === 'string'
                        ? option
                        : (option.value || option.ar || option.en || '');
                    const optionLabel = typeof option === 'string'
                        ? option
                        : localized(option.label || option);
                    control.add(new Option(optionLabel, optionValue));
                });
            } else if (field.type === 'textarea') {
                control = document.createElement('textarea');
                control.className = 'form-control';
                control.rows = 3;
            } else {
                control = document.createElement('input');
                control.className = 'form-control';
                control.type = field.type === 'number' ? 'number' : 'text';
                if (field.type === 'number') {
                    control.min = '0';
                    control.step = 'any';
                }
            }

            control.id = controlId;
            control.name = 'service_answers[' + field.key + ']';
            control.required = Boolean(field.required);
            control.value = value || '';
            wrapper.append(label, control);

            if (field.help) {
                const help = document.createElement('span');
                help.className = 'taqa-request-help';
                help.textContent = localized(field.help);
                wrapper.appendChild(help);
            }

            const error = document.createElement('small');
            error.className = 'taqa-request-error';
            error.dataset.taqaError = '';
            error.hidden = true;
            wrapper.appendChild(error);
            column.appendChild(wrapper);
            form.querySelector('[data-taqa-service-fields-list]').appendChild(column);
        }

        function updateScheduleFields() {
            flexibleInput.value = flexibleCheckbox.checked ? '1' : '0';
            const dateWrap = form.querySelector('[data-taqa-date-wrap]');
            dateWrap.hidden = flexibleCheckbox.checked;
            preferredDate.required = !flexibleCheckbox.checked;
            const today = new Date();
            today.setMinutes(today.getMinutes() - today.getTimezoneOffset());
            preferredDate.min = today.toISOString().slice(0, 10);
        }

        function updateDeliveryFields() {
            const onsite = modeSelect.value === 'onsite';
            onsiteFields.hidden = !onsite;
            form.querySelector('[data-taqa-remote-note]').hidden = onsite;
            form.querySelectorAll('[data-taqa-review-onsite]').forEach(function (row) {
                row.hidden = !onsite;
            });
            const config = serviceConfig(selectedService());
            form.querySelector('[data-taqa-review-solar]').hidden = !config.solar;
            form.querySelector('[data-taqa-review-urgency]').hidden = !config.show_urgency;
            cityOther.hidden = citySelect.value !== 'other' || !onsite;
            updateScheduleFields();
            if (onsite && currentStep === 2) initializeMap();
        }

        function applyServiceConfiguration(service, answers) {
            const config = serviceConfig(service);
            const modes = config.mode === 'onsite' || config.mode === 'remote'
                ? [config.mode]
                : ['onsite', 'remote'];
            const currentMode = modeSelect.value;
            modeSelect.replaceChildren();
            modes.forEach(function (mode) {
                modeSelect.add(new Option(messages.modes[mode], mode));
            });
            modeSelect.value = modes.includes(currentMode) ? currentMode : modes[0];

            const solarField = form.querySelector('[data-taqa-solar-field]');
            const solarSelect = form.querySelector('[name="solar_system_type"]');
            solarField.hidden = Boolean(service && config.solar !== true);
            if (service && config.solar !== true) solarSelect.value = '';

            const urgencyField = form.querySelector('[data-taqa-urgency-field]');
            urgencyField.hidden = !config.show_urgency;
            if (!config.show_urgency) {
                form.querySelector('[name="urgency_level"][value="normal"]').checked = true;
            }

            const fieldsContainer = form.querySelector('[data-taqa-service-fields]');
            const fieldsList = form.querySelector('[data-taqa-service-fields-list]');
            fieldsList.replaceChildren();
            const fields = Array.isArray(config.fields) ? config.fields : [];
            fields.forEach(function (field) {
                if (field && field.key && field.label) {
                    addServiceField(field, answers ? answers[field.key] : '');
                }
            });
            fieldsContainer.hidden = fields.length === 0;
            updateDeliveryFields();
        }

        function updateReview() {
            const service = selectedService();
            const config = serviceConfig(service);
            const urgency = form.querySelector('[name="urgency_level"]:checked');
            const budget = form.querySelector('[name="budget"]').value.trim();
            const onsite = modeSelect.value === 'onsite';
            const latitude = form.querySelector('[name="latitude"]').value;
            const longitude = form.querySelector('[name="longitude"]').value;
            setText('[data-taqa-review="service"]', service ? localized(service.name) : '');
            setText('[data-taqa-review="title"]', form.querySelector('[name="title"]').value.trim() || serviceTitle());
            setText('[data-taqa-review="description"]', form.querySelector('[name="description"]').value.trim());
            setText('[data-taqa-review="mode"]', messages.modes[modeSelect.value]);
            setText('[data-taqa-review="property"]', messages.properties[form.querySelector('[name="property_type"]').value]);
            setText('[data-taqa-review="city"]', onsite ? cityLabel() : '');
            setText('[data-taqa-review="address"]', onsite ? form.querySelector('[name="service_address"]').value.trim() : '');
            setText('[data-taqa-review="coordinates"]', onsite && latitude && longitude
                ? latitude + ', ' + longitude
                : messages.noLocation);
            setText('[data-taqa-review="access"]', onsite ? form.querySelector('[name="access_notes"]').value.trim() : '');
            setText('[data-taqa-review="date"]', flexibleCheckbox.checked
                ? messages.periods.flexible
                : preferredDate.value);
            setText('[data-taqa-review="period"]', messages.periods[form.querySelector('[name="preferred_period"]').value]);
            setText('[data-taqa-review="contact_name"]', form.querySelector('[name="contact_name"]').value.trim());
            setText('[data-taqa-review="contact_phone"]', form.querySelector('[name="contact_phone"]').value.trim());
            setText('[data-taqa-review="system"]', config.solar
                ? messages.systemTypes[form.querySelector('[name="solar_system_type"]').value]
                : '');
            setText('[data-taqa-review="budget"]', budget ? budget + ' ' + messages.currency : '');
            setText('[data-taqa-review="urgency"]', urgency ? urgency.nextElementSibling.textContent.trim() : '');
            setText('[data-taqa-review="notes"]', form.querySelector('[name="additional_notes"]').value.trim());
            setText('[data-taqa-summary="budget"]', budget ? budget + ' ' + messages.currency : '');
            setText('[data-taqa-summary="urgency"]', urgency ? urgency.nextElementSibling.textContent.trim() : '');

            const answerList = form.querySelector('[data-taqa-review-answer-list]');
            const answerSection = form.querySelector('[data-taqa-review-answers]');
            answerList.replaceChildren();
            let answerCount = 0;
            (Array.isArray(config.fields) ? config.fields : []).forEach(function (field) {
                const control = form.querySelector('[name="service_answers[' + field.key + ']"]');
                const value = control ? control.value.trim() : '';
                const row = document.createElement('div');
                const label = document.createElement('dt');
                label.textContent = localized(field.label);
                const answer = document.createElement('dd');
                answer.textContent = value || messages.notSpecified;
                row.append(label, answer);
                answerList.appendChild(row);
                answerCount += 1;
            });
            answerSection.hidden = answerCount === 0;

            const files = Array.from(fileInput.files);
            form.querySelector('[data-taqa-no-files]').hidden = files.length > 0;
            form.querySelectorAll('[data-taqa-review-files], [data-taqa-selected-files]').forEach(function (list) {
                list.replaceChildren();
                files.forEach(function (file) {
                    const item = document.createElement('li');
                    const name = document.createElement('span');
                    name.className = 'taqa-request-file-name';
                    name.textContent = file.name + ' (' + (file.size / 1024 / 1024).toFixed(2) + ' MB)';
                    const preview = document.createElement('button');
                    preview.type = 'button';
                    preview.className = 'taqa-request-file-preview';
                    preview.title = messages.previewFile;
                    preview.setAttribute('aria-label', messages.previewFile + ': ' + file.name);
                    const icon = document.createElement('i');
                    icon.className = 'fa fa-eye';
                    icon.setAttribute('aria-hidden', 'true');
                    preview.appendChild(icon);
                    preview.addEventListener('click', function () { previewFile(file); });
                    item.append(name, preview);
                    list.appendChild(item);
                });
            });
        }

        function clearFilePreview() {
            previewBody.replaceChildren();
            previewDownload.removeAttribute('href');
            if (previewUrl) URL.revokeObjectURL(previewUrl);
            previewUrl = null;
        }

        function previewFile(file) {
            clearFilePreview();
            const extension = file.name.split('.').pop().toLowerCase();
            const types = { jpg: 'image/jpeg', jpeg: 'image/jpeg', png: 'image/png', webp: 'image/webp', pdf: 'application/pdf' };
            const type = types[extension];
            previewUrl = URL.createObjectURL(type ? file.slice(0, file.size, type) : file);
            previewDialog.querySelector('[data-taqa-preview-title]').textContent = file.name;
            previewDownload.href = previewUrl;
            previewDownload.download = file.name;
            if (type && type.startsWith('image/')) {
                const image = document.createElement('img');
                image.src = previewUrl;
                image.alt = file.name;
                previewBody.appendChild(image);
            } else if (type === 'application/pdf') {
                const frame = document.createElement('iframe');
                frame.src = previewUrl;
                frame.title = file.name;
                previewBody.appendChild(frame);
            } else {
                const help = document.createElement('p');
                help.textContent = messages.previewDownloadHelp;
                previewBody.appendChild(help);
            }
            if (!previewDialog.open) previewDialog.showModal();
        }

        function setStep(step) {
            currentStep = step;
            panels.forEach(function (panel) {
                panel.hidden = Number(panel.dataset.taqaPanel) !== currentStep;
            });
            steps.forEach(function (button) {
                const buttonStep = Number(button.dataset.taqaStep);
                button.disabled = buttonStep > currentStep;
                button.classList.toggle('is-active', buttonStep === currentStep);
                button.classList.toggle('is-complete', buttonStep < currentStep);
                if (buttonStep === currentStep) button.setAttribute('aria-current', 'step');
                else button.removeAttribute('aria-current');
            });
            previousButton.hidden = currentStep === 1;
            nextButton.hidden = currentStep === panels.length;
            submitButton.hidden = currentStep !== panels.length;
            if (currentStep === 2) updateDeliveryFields();
            if (currentStep === panels.length) updateReview();
        }

        function clearErrors() {
            form.querySelectorAll('[data-taqa-error]').forEach(function (element) {
                element.textContent = '';
                element.hidden = true;
            });
            form.querySelectorAll('.is-invalid').forEach(function (element) {
                element.classList.remove('is-invalid');
            });
            alertBox.className = 'alert d-none taqa-request-alert';
            alertBox.textContent = '';
        }

        function showFieldError(name, message) {
            const answerMatch = name.match(/^service_answers\.(.+)$/);
            const fieldName = answerMatch
                ? 'service_answers[' + answerMatch[1] + ']'
                : name.replace(/\.\d+$/, '[]');
            const field = form.querySelector('[name="' + fieldName + '"]') ||
                form.querySelector('[name="' + name + '"]');
            const wrapper = field ? field.closest('[data-taqa-field]') : null;
            const error = wrapper ? wrapper.querySelector('[data-taqa-error]') : null;
            if (!error) return null;
            error.textContent = message;
            error.hidden = false;
            const focusField = field === fileInput ? pickFilesButton : field;
            focusField.classList.add('is-invalid');
            return focusField;
        }

        function validateStep(step) {
            clearErrors();
            let firstInvalid = null;
            if (step === 1) {
                if (!serviceSelect.value) {
                    firstInvalid = showFieldError('service_id', servicesFailed ? messages.loadError : messages.selectService);
                }
                const description = form.querySelector('[name="description"]');
                if (!description.value.trim()) {
                    firstInvalid = firstInvalid || showFieldError('description', messages.descriptionRequired);
                }
                const budget = form.querySelector('[name="budget"]');
                if (budget.value !== '' && (!Number.isFinite(Number(budget.value)) || Number(budget.value) < 0)) {
                    firstInvalid = firstInvalid || showFieldError('budget', messages.budgetInvalid);
                }
                const config = serviceConfig(selectedService());
                (Array.isArray(config.fields) ? config.fields : []).forEach(function (field) {
                    const control = form.querySelector('[name="service_answers[' + field.key + ']"]');
                    if (!control) return;
                    const value = control.value.trim();
                    if (field.required && !value) {
                        firstInvalid = firstInvalid || showFieldError('service_answers.' + field.key, messages.answerRequired);
                    } else if (value && field.type === 'number' && (!Number.isFinite(Number(value)) || Number(value) < 0)) {
                        firstInvalid = firstInvalid || showFieldError('service_answers.' + field.key, messages.answerNumberInvalid);
                    }
                });
            }
            if (step === 2) {
                if (!modeSelect.value) {
                    firstInvalid = showFieldError('delivery_mode', messages.modeRequired);
                }
                if (modeSelect.value === 'onsite') {
                    if (!form.querySelector('[name="property_type"]').value) {
                        firstInvalid = firstInvalid || showFieldError('property_type', messages.propertyRequired);
                    }
                    if (!citySelect.value) {
                        firstInvalid = firstInvalid || showFieldError('site_city', messages.cityRequired);
                    }
                    if (citySelect.value === 'other' && !form.querySelector('[name="site_city_other"]').value.trim()) {
                        firstInvalid = firstInvalid || showFieldError('site_city_other', messages.cityOtherRequired);
                    }
                    if (!form.querySelector('[name="service_address"]').value.trim()) {
                        firstInvalid = firstInvalid || showFieldError('service_address', messages.addressRequired);
                    }
                    const latitude = form.querySelector('[name="latitude"]').value;
                    const longitude = form.querySelector('[name="longitude"]').value;
                    if ((latitude === '') !== (longitude === '') ||
                        (latitude !== '' && (!Number.isFinite(Number(latitude)) || Number(latitude) < -90 || Number(latitude) > 90)) ||
                        (longitude !== '' && (!Number.isFinite(Number(longitude)) || Number(longitude) < -180 || Number(longitude) > 180))) {
                        firstInvalid = firstInvalid || showFieldError(latitude === '' ? 'longitude' : 'latitude', messages.coordinatesInvalid);
                    }
                }
                const contactName = form.querySelector('[name="contact_name"]');
                if (!contactName.value.trim()) {
                    firstInvalid = firstInvalid || showFieldError('contact_name', messages.contactRequired);
                }
                const phone = form.querySelector('[name="contact_phone"]').value.trim();
                const phoneDigits = phone.replace(/\D/g, '');
                if (!/^\+?[\d ()-]+$/.test(phone) || phoneDigits.length < 9 || phoneDigits.length > 15) {
                    firstInvalid = firstInvalid || showFieldError('contact_phone', messages.phoneInvalid);
                }
                if (!flexibleCheckbox.checked && (!preferredDate.value || preferredDate.value < preferredDate.min)) {
                    firstInvalid = firstInvalid || showFieldError('preferred_date', messages.dateInvalid);
                }
            }
            if (step === 2 || step === 3) {
                const files = Array.from(fileInput.files);
                if (files.length > 5) {
                    firstInvalid = showFieldError('attachments[]', messages.fileCountError);
                }
                const allowedExtensions = ['jpg', 'jpeg', 'png', 'webp', 'pdf', 'docx'];
                files.forEach(function (file) {
                    const extension = file.name.split('.').pop().toLowerCase();
                    if (!allowedExtensions.includes(extension)) {
                        firstInvalid = firstInvalid || showFieldError('attachments[]', messages.fileTypeError);
                    } else if (file.size > 10 * 1024 * 1024) {
                        firstInvalid = firstInvalid || showFieldError('attachments[]', messages.fileSizeError);
                    }
                });
            }
            if (firstInvalid) {
                firstInvalid.focus();
                return false;
            }
            return true;
        }

        function createMapMarker(latitude, longitude, centerMap) {
            const point = [Number(latitude), Number(longitude)];
            form.querySelector('[name="latitude"]').value = Number(latitude).toFixed(7);
            form.querySelector('[name="longitude"]').value = Number(longitude).toFixed(7);
            if (requestMap && window.L) {
                if (!requestMarker) {
                    requestMarker = L.marker(point, { draggable: true }).addTo(requestMap);
                    requestMarker.on('dragend', function (event) {
                        const position = event.target.getLatLng();
                        createMapMarker(position.lat, position.lng, false);
                    });
                } else {
                    requestMarker.setLatLng(point);
                }
                if (centerMap) requestMap.setView(point, 15);
            }
            form.querySelector('[data-taqa-map-status]').textContent =
                messages.locationSelected + ': ' + Number(latitude).toFixed(5) + ', ' + Number(longitude).toFixed(5);
        }

        function clearMapMarker() {
            if (requestMarker && requestMap) {
                requestMap.removeLayer(requestMarker);
                requestMarker = null;
            }
            form.querySelector('[data-taqa-map-status]').textContent = messages.noLocation;
        }

        function initializeMap() {
            const mapElement = form.querySelector('[data-taqa-map]');
            if (!mapElement) return;
            if (!window.L) {
                form.querySelector('[data-taqa-map-status]').textContent = messages.mapLoadError;
                return;
            }

            const latitude = form.querySelector('[name="latitude"]').value;
            const longitude = form.querySelector('[name="longitude"]').value;
            if (!requestMap) {
                const center = cityCenters[citySelect.value] || [24.7136, 46.6753];
                requestMap = L.map(mapElement, { scrollWheelZoom: false }).setView(center, citySelect.value ? 12 : 5);
                TaqaMaps.addBaseLayers(requestMap);
                requestMap.on('click', function (event) {
                    createMapMarker(event.latlng.lat, event.latlng.lng, false);
                    updateReview();
                });
            }

            if (latitude && longitude) {
                createMapMarker(latitude, longitude, false);
            } else if (requestMarker) {
                clearMapMarker();
            }
            window.setTimeout(function () { requestMap.invalidateSize(); }, 0);
        }

        function syncManualCoordinates() {
            const latitude = form.querySelector('[name="latitude"]').value;
            const longitude = form.querySelector('[name="longitude"]').value;
            if (!latitude && !longitude) {
                clearMapMarker();
            } else if (latitude && longitude &&
                Number.isFinite(Number(latitude)) && Number.isFinite(Number(longitude))) {
                createMapMarker(latitude, longitude, false);
            }
            updateReview();
        }

        function restoreDraft(draft) {
            Object.entries(draft).forEach(function (entry) {
                const name = entry[0];
                const value = entry[1];
                if (name === 'service_id' || name === '__step' || name === 'attachments[]') return;
                const controls = Array.from(form.elements).filter(function (control) {
                    return control.name === name;
                });
                if (!controls.length) return;
                if (controls[0].type === 'radio') {
                    controls.forEach(function (control) { control.checked = control.value === value; });
                } else {
                    controls[0].value = value;
                }
            });
            flexibleCheckbox.checked = flexibleInput.value !== '0';
            updateDeliveryFields();
        }

        function saveDraft() {
            const draft = {};
            new FormData(form).forEach(function (value, name) {
                if (name !== '_token' && name !== 'attachments[]' && !(value instanceof File)) {
                    draft[name] = value;
                }
            });
            draft.__step = currentStep;
            try {
                localStorage.setItem(draftKey, JSON.stringify(draft));
                savedDraft = draft;
                alertBox.className = 'alert alert-success taqa-request-alert';
                alertBox.textContent = messages.draftSaved;
            } catch (error) {
                alertBox.className = 'alert alert-danger taqa-request-alert';
                alertBox.textContent = messages.storageUnavailable;
            }
        }

        async function loadServices() {
            const requestedId = serviceSelect.dataset.selectedService || '';
            const selectedId = requestedId || (savedDraft && savedDraft.service_id) || serviceSelect.value;
            serviceSelect.replaceChildren(new Option(messages.loading, ''));
            serviceSelect.disabled = true;
            try {
                const response = await fetch(@json(route('service-requests.services', [], false)), {
                    headers: { 'Accept': 'application/json' },
                });
                if (!response.ok) throw new Error(messages.loadError);
                const result = await response.json();
                const services = result.data && result.data.services;
                if (!Array.isArray(services)) throw new Error(messages.loadError);

                serviceSelect.replaceChildren(new Option(
                    services.length ? messages.selectService : messages.noServices, ''
                ));
                services.forEach(function (service) {
                    const serviceId = String(service.id);
                    selectedServices.set(serviceId, service);
                    serviceSelect.add(new Option(localized(service.name), serviceId));
                });
                if (services.some(function (service) { return String(service.id) === String(selectedId); })) {
                    serviceSelect.value = selectedId;
                } else if (!selectedId) {
                    const surveyService = services.find(function (service) {
                        const config = serviceConfig(service);
                        const keys = new Set((Array.isArray(config.fields) ? config.fields : [])
                            .map(function (field) { return field.key; }));
                        return config.solar === true &&
                            ['goal', 'existing', 'loads'].every(function (key) { return keys.has(key); });
                    });
                    if (surveyService) serviceSelect.value = String(surveyService.id);
                }
                serviceSelect.disabled = services.length === 0;
                serviceSelect.removeAttribute('data-selected-service');
                servicesFailed = false;
                serviceError.classList.add('d-none');
                applyServiceConfiguration(selectedService());
                if (savedDraft && savedDraft.service_id === serviceSelect.value &&
                    (!requestedId || requestedId === serviceSelect.value)) {
                    restoreDraft(savedDraft);
                    const draftStep = Number(savedDraft.__step);
                    if (draftStep >= 1 && draftStep <= panels.length) setStep(draftStep);
                }
                updateServiceSummary();
                updateReview();
            } catch (error) {
                servicesFailed = true;
                serviceSelect.replaceChildren(new Option(messages.loadError, ''));
                serviceError.textContent = error.message || messages.loadError;
                serviceError.classList.remove('d-none');
            }
        }

        steps.forEach(function (button) {
            button.addEventListener('click', function () {
                const targetStep = Number(button.dataset.taqaStep);
                if (targetStep < currentStep) setStep(targetStep);
            });
        });

        nextButton.addEventListener('click', function () {
            if (!validateStep(currentStep)) return;
            setStep(currentStep + 1);
            form.scrollIntoView({ behavior: 'smooth', block: 'start' });
        });

        previousButton.addEventListener('click', function () {
            if (currentStep > 1) setStep(currentStep - 1);
        });

        saveDraftButton.addEventListener('click', saveDraft);
        pickFilesButton.addEventListener('click', function () { fileInput.click(); });
        previewDialog.querySelector('[data-taqa-close-preview]').addEventListener('click', function () {
            previewDialog.close();
        });
        previewDialog.addEventListener('close', clearFilePreview);
        window.addEventListener('pagehide', function () {
            previewDialog.close();
            clearFilePreview();
        });

        serviceSelect.addEventListener('change', function () {
            applyServiceConfiguration(selectedService());
            updateServiceSummary();
            updateReview();
        });
        modeSelect.addEventListener('change', function () {
            updateDeliveryFields();
            updateReview();
        });
        citySelect.addEventListener('change', function () {
            cityOther.hidden = citySelect.value !== 'other' || modeSelect.value !== 'onsite';
            if (requestMap && !requestMarker && cityCenters[citySelect.value]) {
                requestMap.setView(cityCenters[citySelect.value], 12);
            }
            updateReview();
        });
        flexibleCheckbox.addEventListener('change', updateDeliveryFields);
        form.querySelector('[data-taqa-use-location]').addEventListener('click', function () {
            if (!navigator.geolocation) {
                form.querySelector('[data-taqa-map-status]').textContent = messages.locationUnavailable;
                return;
            }

            navigator.geolocation.getCurrentPosition(function (position) {
                createMapMarker(position.coords.latitude, position.coords.longitude, true);
                updateReview();
            }, function () {
                form.querySelector('[data-taqa-map-status]').textContent = messages.locationUnavailable;
            }, { enableHighAccuracy: true, timeout: 10000 });
        });
        form.querySelectorAll('[name="latitude"], [name="longitude"]').forEach(function (input) {
            input.addEventListener('input', syncManualCoordinates);
        });
        fileInput.addEventListener('change', updateReview);
        form.addEventListener('input', updateReview);
        form.addEventListener('change', updateReview);

        form.addEventListener('submit', async function (event) {
            event.preventDefault();
            clearErrors();
            if (!validateStep(1)) {
                setStep(1);
                return;
            }
            if (!validateStep(2)) {
                setStep(2);
                return;
            }
            if (!validateStep(3)) {
                setStep(3);
                return;
            }

            ensureSuggestedTitle();
            submitButton.disabled = true;
            spinner.classList.remove('d-none');
            try {
                const response = await fetch(form.action, {
                    method: 'POST',
                    body: new FormData(form),
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || '',
                    },
                });
                const result = await response.json().catch(function () { return {}; });
                if (response.ok && result.success) {
                    try {
                        localStorage.removeItem(draftKey);
                        savedDraft = null;
                    } catch (error) {
                    }
                    if (result.redirect_url) {
                        window.location.assign(result.redirect_url);
                        return;
                    }
                    alertBox.className = 'alert alert-success taqa-request-alert';
                    alertBox.textContent = result.message || messages.success;
                    form.reset();
                    serviceSelect.value = '';
                    clearMapMarker();
                    applyServiceConfiguration(null);
                    updateServiceSummary();
                    updateReview();
                    setStep(1);
                    alertBox.scrollIntoView({ behavior: 'smooth', block: 'center' });
                    return;
                }

                const errors = result.errors || (result.data && typeof result.data === 'object' ? result.data : null);
                if (response.status === 422 && errors) {
                    const firstError = Object.entries(errors).find(function (entry) {
                        return Array.isArray(entry[1]) && entry[1].length;
                    });
                    if (firstError) {
                        const field = showFieldError(firstError[0], firstError[1][0]);
                        const panel = field && field.closest('[data-taqa-panel]');
                        if (panel) setStep(Number(panel.dataset.taqaPanel));
                        return;
                    }
                }

                throw new Error(result.message || messages.submitError);
            } catch (error) {
                alertBox.className = 'alert alert-danger taqa-request-alert';
                alertBox.textContent = error.message || messages.connectionError;
            } finally {
                submitButton.disabled = false;
                spinner.classList.add('d-none');
            }
        });

        setStep(1);
        updateReview();
        loadServices();
    });
</script>
