@php
    $academicMajors = config('app.brand') === 'huloolcom' ? \Domain\AppBase\Models\Edu\AcademicMajor::all() : collect();
@endphp

<!-- Service Request Modal -->
<div class="modal fade" id="serviceRequestModal" tabindex="-1" aria-labelledby="serviceRequestModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable modal-lg">
        <div class="modal-content border-0 shadow-lg style-service-modal" style="border-radius: 16px; overflow: visible;">
            <div class="modal-header text-white"
                style="background-color:{{ $platformBrand['primary_color'] }}; padding: 18px 24px;">
                <h5 class="modal-title font-weight-bold d-flex align-items-center" id="serviceRequestModalLabel"
                    style="font-size: 1.25rem;">
                    <i class="fa fa-file-text-o me-2" style="font-size: 1.3rem;"></i>
                    @lang(config('app.brand') === 'huloolcom' ? 'ecommerce::ecommerce.sr_title_hulool' : 'ecommerce::ecommerce.sr_title_taqa')
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"
                    style="filter: brightness(0) invert(1);"></button>
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

                <div class="modal-body p-4" style="max-height: 80vh; overflow-y: auto; background-color: #f8f9fa;">

                    <div id="serviceRequestAlert" class="alert d-none" role="alert"></div>

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
                                required disabled style="border-radius: 10px; font-size: 0.95rem;">
                                <option value="">@lang('ecommerce::ecommerce.loading')</option>
                            </select>
                        </div>

                        <!-- Budget -->
                        <div class="col-md-6">
                            <label for="sr_budget" class="form-label font-weight-bold text-dark mb-1">
                                @lang('ecommerce::ecommerce.sr_budget_label')
                            </label>
                            <div class="input-group">
                                <input type="number" dir="ltr" step="0.01" min="0"
                                    class="form-control form-control-lg border-2" id="sr_budget" name="budget"
                                    placeholder="@lang('ecommerce::ecommerce.sr_budget_placeholder')"
                                    style="border-top-right-radius: 10px; border-bottom-right-radius: 10px; font-size: 0.95rem;">
                                <span class="input-group-text bg-white border-2"
                                    style="border-top-left-radius: 10px; border-bottom-left-radius: 10px;">@lang('ecommerce::ecommerce.currency_symbol')</span>
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

                </div>

                <div class="modal-footer bg-white border-top p-3 d-flex justify-content-between">
                    <button type="button" class="btn btn-light btn-lg px-4 border" data-bs-dismiss="modal"
                        style="border-radius: 10px; font-size: 0.95rem;">
                        @lang('ecommerce::ecommerce.sr_cancel')
                    </button>
                    <button type="submit" id="sr_submit_btn" class="btn btn-danger btn-lg px-5 font-weight-bold"
                        style="background: #306FAA;border-color: #306FAA;color: #ffffff;; border-radius: 10px; font-size: 0.95rem;">
                        <span id="sr_submit_spinner" class="spinner-border spinner-border-sm me-2 d-none"
                            role="status" aria-hidden="true"></span>
                        <i class="fa fa-paper-plane me-1"></i> @lang('ecommerce::ecommerce.sr_submit')
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('serviceRequestForm');
        const alertBox = document.getElementById('serviceRequestAlert');
        const submitBtn = document.getElementById('sr_submit_btn');
        const submitSpinner = document.getElementById('sr_submit_spinner');

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
        let servicesLoading = false;
        let servicesError = false;

        async function loadServices() {
            if (servicesLoading) return;
            servicesLoading = true;
            servicesError = false;
            const selected = serviceSelect.value;
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

                serviceSelect.disabled = services.length === 0;
            } catch (error) {
                servicesError = true;
                serviceSelect.replaceChildren(new Option(messages.servicesError, ''));
            } finally {
                servicesLoading = false;
            }
        }

        loadServices();

        document.getElementById('serviceRequestModal').addEventListener('show.bs.modal', loadServices);

        function clearFieldErrors() {
            form.querySelectorAll('.sr-field-error').forEach(function(el) {
                el.remove();
            });

            form.querySelectorAll('.is-invalid').forEach(function(el) {
                el.classList.remove('is-invalid');
            });
        }

        function setFieldError(field, message) {
            if (!field) return;

            const wrapper = field.closest('.col-12, .col-md-6') || field.parentElement;
            field.classList.add('is-invalid');

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

                    const err = field.closest('.col-12, .col-md-6')?.querySelector(
                        '.sr-field-error'
                    );

                    if (err) err.remove();
                });
            });
        });

        function validateForm() {
            clearFieldErrors();

            let valid = true;
            let firstInvalid = null;

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
            });
        }

        form.addEventListener('submit', function(e) {
            e.preventDefault();

            // Reset alert
            alertBox.classList.add('d-none');
            alertBox.className = 'alert d-none';
            alertBox.innerHTML = '';

            // Inline validation instead of browser popups
            if (!validateForm()) return;

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
                        alertBox.className = 'd-none';
                        alertBox.innerHTML = '';
                        form.reset();

                        const modalEl = document.getElementById('serviceRequestModal');

                        if (modalEl) {
                            if (typeof bootstrap !== 'undefined' && bootstrap.Modal) {
                                let instance = typeof bootstrap.Modal.getInstance ===
                                    'function' ? bootstrap.Modal.getInstance(modalEl) : null;

                                if (instance) {
                                    instance.hide();
                                } else if (typeof $ !== 'undefined' && $.fn.modal) {
                                    $(modalEl).modal('hide');
                                }
                            } else if (typeof $ !== 'undefined' && $.fn.modal) {
                                $(modalEl).modal('hide');
                            }
                        }

                        const successMsg = data.message || messages.successMsg;

                        if (typeof showNotification === 'function') {
                            showNotification(successMsg, 'success');
                        } else if (typeof toastr !== 'undefined') {
                            toastr.success(successMsg);
                        } else {
                            alert(successMsg);
                        }

                        // Refresh the service requests list live if the user is on that page
                        if (/\/account\/service-requests/.test(window.location.pathname)) {
                            if (typeof up !== 'undefined' && typeof up.reload === 'function') {
                                up.reload('.dashboard-content');
                            } else {
                                window.location.reload();
                            }
                        }
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
