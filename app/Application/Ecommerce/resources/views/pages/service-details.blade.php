@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.sr_workspace_services'))

@section('active-nav', 'services')

@section('content')
    <div class="sr-detail-page"
        dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'rtl' : 'ltr' }}"
        data-sr-service-details
        data-service-url="{{ route('service-requests.service-data', ['serviceUuid' => $serviceUuid], false) }}"
        data-request-url="{{ route('service-requests.create', [], false) }}">
        <div class="container">
            <nav class="sr-detail-breadcrumbs" aria-label="@lang('ecommerce::ecommerce.sr_detail_breadcrumbs')">
                <a href="{{ route('service-requests.catalog') }}" up-follow="false">
                    @lang('ecommerce::ecommerce.sr_workspace_services')
                </a>
                <i class="fa fa-angle-{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'left' : 'right' }}"
                    aria-hidden="true"></i>
                <span aria-current="page" data-service-name></span>
            </nav>

            <p class="sr-detail-state" data-detail-loading aria-live="polite">
                @lang('ecommerce::ecommerce.sr_detail_loading')
            </p>
            <div class="sr-detail-state" data-detail-error role="alert" hidden>
                <p>@lang('ecommerce::ecommerce.sr_detail_load_error')</p>
                <button class="sr-detail-request-button" type="button" data-detail-retry>
                    @lang('ecommerce::ecommerce.sr_detail_retry')
                </button>
            </div>

            <div class="sr-detail-top" data-detail-content hidden>
                <div class="sr-detail-main">
                    <section class="sr-service-banner">
                        <div class="sr-service-banner-copy">
                            <h1 data-service-name></h1>
                            <p data-service-description hidden></p>
                            <div class="sr-detail-badges">
                                <span class="sr-detail-badge is-available">
                                    <i class="fa fa-check-circle" aria-hidden="true"></i>
                                    @lang('ecommerce::ecommerce.sr_catalog_available')
                                </span>
                                <span class="sr-detail-badge" data-service-mode hidden></span>
                            </div>
                        </div>
                    </section>

                    <section class="sr-detail-panel" data-detail-section="scope" hidden>
                        <div class="sr-detail-panel-title">
                            <span class="sr-detail-section-icon" aria-hidden="true"><i class="fa fa-clipboard"></i></span>
                            <h2>@lang('ecommerce::ecommerce.sr_detail_scope')</h2>
                        </div>
                        <ul class="sr-detail-list" data-detail-list="scope"></ul>
                    </section>

                </div>

                <aside class="sr-detail-aside">
                    <div class="sr-detail-fact" data-detail-fact="mode" hidden>
                        <span>@lang('ecommerce::ecommerce.sr_detail_delivery_mode')</span>
                        <strong data-detail-value="mode"></strong>
                    </div>
                    <a class="sr-detail-request-button"
                        href="{{ route('service-requests.create') }}"
                        up-follow="false">
                        <i class="fa fa-plus-circle" aria-hidden="true"></i>
                        @lang('ecommerce::ecommerce.sr_catalog_request')
                    </a>
                </aside>
            </div>
        </div>
    </div>

    <script>
        (() => {
            const page = document.querySelector('[data-sr-service-details]');
            if (!page) return;

            const locale = @json(app()->getLocale());
            const modeLabels = {
                onsite: @json(__('ecommerce::ecommerce.sr_form_onsite')),
                remote: @json(__('ecommerce::ecommerce.sr_form_remote')),
                both: @json(__('ecommerce::ecommerce.sr_detail_mode_both'))
            };
            const loading = page.querySelector('[data-detail-loading]');
            const error = page.querySelector('[data-detail-error]');
            const content = page.querySelector('[data-detail-content]');
            const requestLink = page.querySelector('a.sr-detail-request-button');

            function localized(value) {
                if (typeof value === 'string') return value.trim();
                if (!value || typeof value !== 'object') return '';
                const text = value[locale] || value.ar || value.en || '';
                return typeof text === 'string' ? text.trim() : '';
            }

            function text(selector, value) {
                const element = page.querySelector(selector);
                if (!element) return;
                element.textContent = value;
                element.hidden = !value;
            }

            function fillList(key, values) {
                const section = page.querySelector('[data-detail-section="' + key + '"]');
                const list = page.querySelector('[data-detail-list="' + key + '"]');
                const localizedItems = values && typeof values === 'object' && !Array.isArray(values)
                    ? values[locale] || values.ar || values.en || []
                    : values;
                const items = Array.isArray(localizedItems)
                    ? localizedItems.map(localized).filter(Boolean)
                    : localized(localizedItems).split(/\r\n|\r|\n/).map((item) => item.trim()).filter(Boolean);

                list.replaceChildren(...items.map((item) => {
                    const row = document.createElement('li');
                    row.textContent = item;
                    return row;
                }));
                section.hidden = items.length === 0;
            }

            function renderService(service) {
                const config = service.request_config && typeof service.request_config === 'object'
                    ? service.request_config
                    : {};
                const modeLabel = modeLabels[config.mode] || '';
                page.querySelectorAll('[data-service-name]').forEach((element) => {
                    element.textContent = localized(service.name);
                });
                text('[data-service-description]', localized(service.description));
                text('[data-service-mode]', modeLabel);
                fillList('scope', service.scope_of_work);
                text('[data-detail-value="mode"]', modeLabel);
                page.querySelector('[data-detail-fact="mode"]').hidden = !modeLabel;

                const requestUrl = new URL(page.dataset.requestUrl, window.location.origin);
                requestUrl.searchParams.set('service', service.id);
                requestLink.href = requestUrl.toString();
                document.title = (localized(service.name) || document.title) + ' - ' + @json(__('ecommerce::ecommerce.site_name'));
                content.hidden = false;
            }

            async function loadService() {
                loading.hidden = false;
                error.hidden = true;
                content.hidden = true;
                try {
                    const response = await fetch(page.dataset.serviceUrl, {
                        headers: { Accept: 'application/json' },
                        credentials: 'same-origin'
                    });
                    if (!response.ok) throw new Error('Service details request failed');

                    const payload = await response.json();
                    const service = payload && payload.data && payload.data.service;
                    if (!service) throw new Error('Service details were missing');

                    renderService(service);
                    loading.hidden = true;
                } catch (requestError) {
                    loading.hidden = true;
                    error.hidden = false;
                }
            }

            page.querySelector('[data-detail-retry]').addEventListener('click', loadService);
            loadService();
        })();
    </script>

    <style>
        .sr-detail-state {
            padding: 18px;
            border: 1px solid #dfe7ee;
            border-radius: 12px;
            background: #fff;
            color: #52697c;
        }

        [hidden] {
            display: none !important;
        }

        .sr-detail-page {
            min-height: 70vh;
            padding: 22px 0 52px;
            background: #f3f6fa;
        }

        .sr-detail-page > .container {
            max-width: 1120px;
        }

        .sr-detail-breadcrumbs {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 18px;
            color: #78899a;
            font-size: 12px;
        }

        .sr-detail-breadcrumbs a {
            color: #397bb2;
            font-weight: 700;
            text-decoration: none;
        }

        .sr-detail-breadcrumbs span {
            overflow: hidden;
            color: #52697c;
            text-overflow: ellipsis;
            white-space: nowrap;
        }

        .sr-detail-top {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 310px;
            align-items: start;
            gap: 20px;
        }

        .sr-detail-main {
            display: grid;
            min-width: 0;
            gap: 16px;
        }

        .sr-service-banner,
        .sr-detail-panel,
        .sr-detail-aside {
            border: 1px solid #dfe7ee;
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 5px 18px rgba(30, 55, 75, .04);
        }

        .sr-service-banner {
            display: flex;
            align-items: flex-start;
            gap: 18px;
            padding: 24px;
            background: linear-gradient(115deg, #eaf3fa, #fff 78%);
        }

        .sr-detail-icon,
        .sr-detail-section-icon {
            display: grid;
            flex: 0 0 48px;
            place-items: center;
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: #dcecf7;
            color: #347bb2;
            font-size: 21px;
        }

        .sr-service-banner-copy {
            min-width: 0;
        }

        .sr-detail-category {
            color: #397bb2;
            font-size: 11px;
            font-weight: 800;
        }

        .sr-service-banner h1 {
            margin: 5px 0 7px;
            color: #193449;
            font-size: clamp(22px, 3vw, 29px);
            font-weight: 800;
            line-height: 1.5;
        }

        .sr-service-banner p {
            margin: 0;
            color: #6d7f90;
            font-size: 13px;
            line-height: 1.9;
        }

        .sr-detail-badges {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 13px;
        }

        .sr-detail-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 5px 9px;
            border: 1px solid #e0e8ee;
            border-radius: 20px;
            background: #fff;
            color: #597083;
            font-size: 10px;
            font-weight: 700;
        }

        .sr-detail-badge.is-available {
            border-color: #cce8d7;
            background: #eff9f2;
            color: #287447;
        }

        .sr-detail-panel {
            padding: 20px 22px;
        }

        .sr-detail-panel h2,
        .sr-detail-aside h2 {
            margin: 0;
            color: #20394c;
            font-size: 16px;
            font-weight: 800;
        }

        .sr-detail-panel-title {
            display: flex;
            align-items: center;
            gap: 11px;
        }

        .sr-detail-section-icon {
            flex-basis: 38px;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            font-size: 16px;
        }

        .sr-detail-list {
            display: grid;
            gap: 9px;
            margin: 15px 0 0;
            padding-inline-start: 20px;
            color: #566d80;
            font-size: 12px;
            line-height: 1.8;
        }

        .sr-detail-list li::marker {
            color: #347bb2;
        }

        .sr-detail-list.is-muted li::marker {
            color: #91a0ad;
        }

        .sr-detail-two-col {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px;
        }

        .sr-detail-note,
        .sr-detail-aside > p,
        .sr-detail-disclaimer {
            color: #8391a0;
            font-size: 11px;
            line-height: 1.8;
        }

        .sr-detail-note {
            margin: 12px 0 0;
        }

        .sr-detail-flow {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
            margin-top: 16px;
        }

        .sr-detail-flow > div {
            padding: 14px;
            border: 1px solid #edf1f4;
            border-radius: 10px;
            background: #fafcfd;
        }

        .sr-detail-flow > div > span {
            color: #397bb2;
            font-size: 17px;
        }

        .sr-detail-flow h3 {
            margin: 8px 0 4px;
            color: #344e62;
            font-size: 12px;
            font-weight: 800;
        }

        .sr-detail-flow p {
            margin: 0;
            color: #7b8b99;
            font-size: 10px;
            line-height: 1.8;
        }

        .sr-detail-aside {
            position: sticky;
            top: 20px;
            padding: 21px;
        }

        .sr-detail-aside > p {
            margin: 8px 0 17px;
        }

        .sr-detail-fact {
            display: grid;
            gap: 5px;
            padding: 13px 0;
            border-top: 1px solid #edf1f4;
        }

        .sr-detail-fact span {
            color: #7b8a98;
            font-size: 11px;
        }

        .sr-detail-fact strong {
            color: #344e62;
            font-size: 12px;
            line-height: 1.7;
        }

        .sr-detail-request-button {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            min-height: 44px;
            margin-top: 13px;
            padding: 10px 14px;
            border: 1px solid #347bb2;
            border-radius: 9px;
            background: #347bb2;
            color: #fff;
            font-size: 12px;
            font-weight: 800;
            text-align: center;
            text-decoration: none;
        }

        .sr-detail-request-button:hover {
            border-color: #28699b;
            background: #28699b;
            color: #fff;
        }

        .sr-detail-disclaimer {
            margin: 12px 0 0;
            text-align: center;
        }

        @media (max-width: 900px) {
            .sr-detail-top {
                grid-template-columns: minmax(0, 1fr);
            }

            .sr-detail-aside {
                position: static;
            }
        }

        @media (max-width: 575px) {
            .sr-detail-two-col,
            .sr-detail-flow {
                grid-template-columns: minmax(0, 1fr);
            }

            .sr-service-banner {
                gap: 12px;
                padding: 18px;
            }

            .sr-detail-icon {
                flex-basis: 40px;
                width: 40px;
                height: 40px;
                font-size: 17px;
            }

            .sr-detail-panel,
            .sr-detail-aside {
                padding: 17px;
            }
        }
    </style>
@endsection
