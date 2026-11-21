<style>
    .sr-catalog-page {
        min-height: 70vh;
        padding: 22px 0 48px;
        background: #f3f6fa;
    }

    .sr-catalog-page > .container {
        max-width: 1040px;
    }

    .sr-catalog-hero {
        position: relative;
        display: flex;
        align-items: center;
        justify-content: space-between;
        min-height: 210px;
        margin-bottom: 28px;
        padding: 30px 38px;
        overflow: hidden;
        border: 1px solid #dce8f1;
        border-radius: 16px;
        background: linear-gradient(110deg, #e5f0f8 0%, #f5f9fc 58%, #e8f2f8 100%);
    }

    .sr-catalog-hero-copy {
        position: relative;
        z-index: 1;
        max-width: 570px;
    }

    .sr-catalog-hero-copy > span {
        color: #397bb2;
        font-size: 12px;
        font-weight: 800;
    }

    .sr-catalog-hero h1 {
        margin: 8px 0;
        color: #172f43;
        font-size: clamp(26px, 3.2vw, 36px);
        font-weight: 800;
        line-height: 1.4;
    }

    .sr-catalog-hero p {
        max-width: 500px;
        margin: 0;
        color: #667b8e;
        font-size: 14px;
        line-height: 1.9;
    }

    .sr-catalog-hero-art {
        display: grid;
        flex: 0 0 150px;
        place-items: center;
        width: 150px;
        height: 150px;
        margin-inline-start: 24px;
        border: 1px solid rgba(255, 255, 255, .8);
        border-radius: 28px;
        background: linear-gradient(145deg, #ffffff, #dcebf5);
        box-shadow: 0 16px 34px rgba(47, 105, 150, .13);
        color: #347bb2;
        font-size: 62px;
    }

    .sr-catalog-section-heading {
        display: flex;
        align-items: flex-end;
        justify-content: space-between;
        gap: 20px;
        margin-bottom: 18px;
    }

    .sr-catalog-section-heading h2 {
        margin: 0;
        color: #183247;
        font-size: 22px;
        font-weight: 800;
    }

    .sr-catalog-section-heading p {
        margin: 4px 0 0;
        color: #8290a0;
        font-size: 12px;
    }

    .sr-catalog-search {
        display: flex;
        align-items: center;
        gap: 9px;
        width: min(280px, 100%);
        height: 42px;
        padding: 0 13px;
        border: 1px solid #dce4ec;
        border-radius: 9px;
        background: #fff;
        color: #7a8a99;
    }

    .sr-catalog-search input {
        width: 100%;
        min-width: 0;
        border: 0;
        outline: 0;
        background: transparent;
        color: #26394a;
        font-size: 12px;
    }

    .sr-catalog-grid {
        display: grid;
        grid-template-columns: repeat(3, minmax(0, 1fr));
        gap: 16px;
    }

    .sr-service-card {
        display: flex;
        min-width: 0;
        min-height: 285px;
        flex-direction: column;
        padding: 18px;
        border: 1px solid #dce5ed;
        border-radius: 13px;
        background: #fff;
        box-shadow: 0 4px 15px rgba(30, 55, 75, .035);
        transition: border-color .18s ease, box-shadow .18s ease, transform .18s ease;
    }

    .sr-service-card:hover {
        transform: translateY(-2px);
        border-color: #b8d0e2;
        box-shadow: 0 10px 24px rgba(30, 55, 75, .08);
    }

    .sr-service-card-top {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 15px;
    }

    .sr-service-icon {
        display: grid;
        place-items: center;
        width: 42px;
        height: 42px;
        border-radius: 12px;
        background: #edf5fb;
        color: #337bb2;
        font-size: 19px;
    }

    .sr-service-availability {
        padding: 4px 9px;
        border-radius: 5px;
        background: #eef5fb;
        color: #3978a8;
        font-size: 10px;
        font-weight: 700;
    }

    .sr-service-card h3 {
        min-height: 48px;
        margin: 0 0 8px;
        color: #193449;
        font-size: 16px;
        font-weight: 800;
        line-height: 1.6;
    }

    .sr-service-description {
        display: -webkit-box;
        min-height: 42px;
        margin: 0 0 14px;
        overflow: hidden;
        color: #718294;
        font-size: 12px;
        line-height: 1.8;
        -webkit-box-orient: vertical;
        -webkit-line-clamp: 2;
    }

    .sr-service-details {
        margin: auto 0 13px;
        padding: 11px 0 0;
        border-top: 1px solid #edf1f4;
    }

    .sr-service-details[hidden] {
        display: none !important;
    }

    .sr-service-details h4 {
        margin: 0 0 5px;
        color: #51677a;
        font-size: 11px;
        font-weight: 800;
    }

    .sr-service-details p {
        margin: 0;
        color: #718294;
        font-size: 11px;
        line-height: 1.8;
        white-space: pre-line;
    }

    .sr-service-card-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 8px;
        margin-top: auto;
        padding-top: 12px;
        border-top: 1px solid #edf1f4;
    }

    .sr-service-card-actions a,
    .sr-service-card-actions button {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-height: 40px;
        padding: 7px 10px;
        border: 1px solid #dce5ed;
        border-radius: 8px;
        background: #fff;
        color: #28465c;
        font-size: 11px;
        font-weight: 800;
        text-align: center;
        text-decoration: none;
        transition: background .18s ease, border-color .18s ease, color .18s ease;
    }

    .sr-service-card-actions a {
        border-color: #347bb2;
        background: #347bb2;
        color: #fff;
    }

    .sr-service-card-actions a:hover {
        border-color: #28699b;
        background: #28699b;
        color: #fff;
    }

    .sr-service-card-actions button:hover {
        border-color: #b8d0e2;
        background: #f5f9fc;
    }

    .sr-catalog-state {
        grid-column: 1 / -1;
        padding: 36px 20px;
        border: 1px dashed #ccd9e4;
        border-radius: 13px;
        background: #fff;
        color: #718294;
        font-size: 14px;
        text-align: center;
    }

    .sr-catalog-state.is-error {
        border-color: #e6b9b9;
        color: #a63d3d;
    }

    .sr-catalog-retry {
        display: inline-flex;
        margin-top: 12px;
        padding: 8px 14px;
        border: 1px solid #dce5ed;
        border-radius: 8px;
        background: #fff;
        color: #28465c;
        font-weight: 700;
    }

    @media (max-width: 900px) {
        .sr-catalog-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }
    }

    @media (max-width: 620px) {
        .sr-catalog-hero {
            min-height: 0;
            padding: 23px;
        }

        .sr-catalog-hero-art {
            flex-basis: 74px;
            width: 74px;
            height: 74px;
            margin-inline-start: 12px;
            border-radius: 18px;
            font-size: 34px;
        }

        .sr-catalog-section-heading {
            align-items: stretch;
            flex-direction: column;
            gap: 12px;
        }

        .sr-catalog-search {
            width: 100%;
        }
    }

    @media (max-width: 480px) {
        .sr-catalog-grid {
            grid-template-columns: minmax(0, 1fr);
        }

        .sr-service-card {
            min-height: 260px;
        }

        .sr-catalog-hero-art {
            display: none;
        }
    }
</style>

<div class="sr-catalog-page">
    <div class="container">
        <section class="sr-catalog-hero">
            <div class="sr-catalog-hero-copy">
                <span>@lang('ecommerce::ecommerce.sr_catalog_eyebrow')</span>
                <h1>@lang('ecommerce::ecommerce.sr_catalog_title')</h1>
                <p>@lang('ecommerce::ecommerce.sr_catalog_intro')</p>
            </div>
            <div class="sr-catalog-hero-art" aria-hidden="true">
                <i class="fa fa-sun-o"></i>
            </div>
        </section>

        <section aria-labelledby="sr-catalog-heading">
            <div class="sr-catalog-section-heading">
                <div>
                    <h2 id="sr-catalog-heading">@lang('ecommerce::ecommerce.sr_catalog_title')</h2>
                    <p>@lang('ecommerce::ecommerce.sr_catalog_intro')</p>
                </div>
                <label class="sr-catalog-search">
                    <i class="fa fa-search" aria-hidden="true"></i>
                    <input id="sr-catalog-search" type="search"
                        placeholder="@lang('ecommerce::ecommerce.sr_catalog_search')"
                        aria-label="@lang('ecommerce::ecommerce.sr_catalog_search')" disabled>
                </label>
            </div>

            <div id="sr-catalog-grid" class="sr-catalog-grid" aria-live="polite">
                <div class="sr-catalog-state">@lang('ecommerce::ecommerce.sr_catalog_loading')</div>
            </div>
        </section>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const grid = document.getElementById('sr-catalog-grid');
        const search = document.getElementById('sr-catalog-search');
        const catalogUrl = @json(route('service-requests.services', [], false));
        const formUrl = @json(route('service-requests.create', [], false));
        const locale = @json(app()->getLocale());
        const messages = {
            loadError: @json(__('ecommerce::ecommerce.sr_catalog_load_error')),
            empty: @json(__('ecommerce::ecommerce.sr_catalog_empty')),
            noMatch: @json(__('ecommerce::ecommerce.sr_catalog_no_match')),
            available: @json(__('ecommerce::ecommerce.sr_catalog_available')),
            request: @json(__('ecommerce::ecommerce.sr_catalog_request')),
            details: @json(__('ecommerce::ecommerce.sr_catalog_details')),
            hideDetails: @json(__('ecommerce::ecommerce.sr_catalog_hide_details')),
            scope: @json(__('ecommerce::ecommerce.sr_catalog_scope')),
            noDetails: @json(__('ecommerce::ecommerce.sr_catalog_no_details')),
            retry: @json(__('ecommerce::ecommerce.sr_catalog_retry')),
        };

        let services = [];

        function localized(value) {
            if (typeof value === 'string') return value;
            if (!value || typeof value !== 'object') return '';
            return value[locale] || value.ar || value.en || '';
        }

        function showState(message, isError) {
            grid.replaceChildren();
            const state = document.createElement('div');
            state.className = 'sr-catalog-state' + (isError ? ' is-error' : '');
            state.textContent = message;
            grid.appendChild(state);
            if (isError) {
                const retry = document.createElement('button');
                retry.type = 'button';
                retry.className = 'sr-catalog-retry';
                retry.textContent = messages.retry;
                retry.addEventListener('click', loadServices);
                state.appendChild(document.createElement('br'));
                state.appendChild(retry);
            }
        }

        function createCard(service) {
            const name = localized(service.name);
            const description = localized(service.description);
            const scope = localized(service.scope_of_work);
            const card = document.createElement('article');
            card.className = 'sr-service-card';
            card.dataset.searchText = [name, description, scope].join(' ').toLocaleLowerCase();

            const top = document.createElement('div');
            top.className = 'sr-service-card-top';
            const icon = document.createElement('span');
            icon.className = 'sr-service-icon';
            icon.innerHTML = '<i class="fa fa-bolt" aria-hidden="true"></i>';
            const badge = document.createElement('span');
            badge.className = 'sr-service-availability';
            badge.textContent = messages.available;
            top.append(icon, badge);

            const title = document.createElement('h3');
            title.textContent = name;
            const summary = document.createElement('p');
            summary.className = 'sr-service-description';
            summary.textContent = description || messages.noDetails;

            const detailPanel = document.createElement('div');
            detailPanel.className = 'sr-service-details';
            detailPanel.hidden = true;
            const scopeTitle = document.createElement('h4');
            scopeTitle.textContent = messages.scope;
            const detailText = document.createElement('p');
            detailText.textContent = scope || description || messages.noDetails;
            detailPanel.append(scopeTitle, detailText);

            const actions = document.createElement('div');
            actions.className = 'sr-service-card-actions';
            const requestLink = document.createElement('a');
            requestLink.textContent = messages.request;
            const requestUrl = new URL(formUrl, window.location.origin);
            requestUrl.searchParams.set('service', service.id);
            requestLink.href = requestUrl.href;
            requestLink.setAttribute('up-follow', 'false');

            const detailsButton = document.createElement('button');
            detailsButton.type = 'button';
            detailsButton.textContent = messages.details;
            detailsButton.setAttribute('aria-expanded', 'false');
            detailsButton.addEventListener('click', function() {
                const isOpen = detailPanel.hidden;
                detailPanel.hidden = !isOpen;
                detailsButton.setAttribute('aria-expanded', String(isOpen));
                detailsButton.textContent = isOpen ? messages.hideDetails : messages.details;
            });
            actions.append(requestLink, detailsButton);
            card.append(top, title, summary, detailPanel, actions);
            return card;
        }

        function renderCards() {
            const query = search.value.trim().toLocaleLowerCase();
            const visible = services.filter(function(service) {
                return service.card.dataset.searchText.includes(query);
            });
            grid.replaceChildren();
            if (!visible.length) {
                showState(query ? messages.noMatch : messages.empty, false);
                return;
            }
            visible.forEach(function(service) {
                grid.appendChild(service.card);
            });
        }

        async function loadServices() {
            search.disabled = true;
            showState(@json(__('ecommerce::ecommerce.sr_catalog_loading')), false);
            try {
                const response = await fetch(catalogUrl, {
                    headers: { 'Accept': 'application/json' }
                });
                if (!response.ok) throw new Error(messages.loadError);
                const result = await response.json();
                if (!Array.isArray(result.data?.services)) throw new Error(messages.loadError);

                services = result.data.services.map(function(service) {
                    return { ...service, card: createCard(service) };
                });
                search.disabled = false;
                if (!services.length) {
                    showState(messages.empty, false);
                    return;
                }
                renderCards();
            } catch (error) {
                showState(messages.loadError, true);
            }
        }

        search.addEventListener('input', renderCards);
        loadServices();
    });
</script>
