@if(request()->hasHeader('X-Up-Target'))
<div class="dashboard-content">
@endif

<style>
    .taqa-my-requests { padding: 0; min-width: 0; background: transparent; color: #193449; }
    .taqa-requests-wrap { width: 100%; min-width: 0; }
    .taqa-requests-heading { display: flex; align-items: flex-start; justify-content: space-between; gap: 20px; margin-bottom: 26px; }
    .taqa-requests-heading h1 { margin: 12px 0 8px; color: #193449; font-size: 28px; font-weight: 800; line-height: 1.5; }
    .taqa-requests-heading p { margin: 0; color: #7b8da0; font-size: 14px; }
    .taqa-requests-account-link { color: #306faa; font-size: 14px; font-weight: 700; text-decoration: none; }
    .taqa-requests-new { display: inline-flex; flex-shrink: 0; align-items: center; gap: 10px; min-height: 44px; padding: 10px 18px; border: 1px solid #306faa; border-radius: 8px; background: #306faa; color: #fff; font-size: 14px; font-weight: 700; text-decoration: none; }
    .taqa-requests-new:hover { background: #245c8e; color: #fff; }
    .taqa-requests-card { overflow: hidden; border: 1px solid #d9e4ef; border-radius: 15px; background: #fff; }
    .taqa-requests-toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; padding: 19px 22px; }
    .taqa-requests-search { display: flex; align-items: center; width: 300px; max-width: 100%; min-height: 45px; border: 1px solid #d9e4ef; border-radius: 10px; }
    .taqa-requests-search input { width: 100%; min-width: 0; padding: 11px 0 11px 12px; border: 0; border-radius: 10px; background: transparent; color: #193449; font: inherit; font-size: 14px; outline: none; }
    .taqa-requests-search input::placeholder { color: #7b8da0; }
    .taqa-requests-search button { flex-shrink: 0; width: 42px; align-self: stretch; border: 0; border-radius: 10px; background: transparent; color: #7b8da0; }
    .taqa-requests-search:focus-within { border-color: #80b5df; box-shadow: 0 0 0 3px rgba(48,111,170,.15); }
    .taqa-requests-filter { width: 195px; max-width: 100%; min-height: 42px; padding: 9px 14px; border: 1px solid #d9e4ef; border-radius: 8px; background-color: #fff; color: #35526e; font-size: 14px; }
    .taqa-my-requests a:focus-visible, .taqa-my-requests button:focus-visible, .taqa-requests-filter:focus-visible { outline: 3px solid #80b5df; outline-offset: 3px; }
    .taqa-requests-scroll { overflow-x: auto; }
    .taqa-requests-table { width: 100%; min-width: 850px; margin: 0; border-collapse: collapse; text-align: start; }
    .taqa-requests-table th, .taqa-requests-table td { text-align: start; }
    .taqa-requests-table th { padding: 15px 20px; border-block: 1px solid #d9e4ef; background: #f6f8fb; color: #7b8da0; font-size: 14px; font-weight: 400; white-space: nowrap; }
    .taqa-requests-table th:first-child { width: 38%; }
    .taqa-requests-table td { padding: 20px; border-bottom: 1px solid #e7edf4; color: #35526e; font-size: 14px; vertical-align: middle; }
    .taqa-requests-table tbody tr:last-child td { border-bottom: 0; }
    .taqa-requests-table tbody tr:hover { background: #fbfcfe; }
    .taqa-request-title { color: #306faa; font-weight: 700; text-decoration: none; overflow-wrap: anywhere; }
    .taqa-request-sub { display: block; margin-top: 5px; color: #7b8da0; font-size: 12px; }
    .taqa-request-pill { display: inline-block; padding: 5px 10px; border-radius: 5px; background: #eef1f5; color: #7b8da0; font-size: 12px; white-space: nowrap; }
    .taqa-request-pill.is-blue { background: #edf4fc; color: #306faa; }
    .taqa-request-pill.is-green { background: #eaf5ee; color: #28765e; }
    .taqa-request-pill.is-red { background: #fff0f0; color: #b54747; }
    .taqa-request-pill.is-amber { background: #fff6e6; color: #93641c; }
    .taqa-request-view { display: inline-block; padding: 8px 13px; border: 1px solid #d9e4ef; border-radius: 8px; background: #fff; color: #193449; font-size: 14px; font-weight: 700; text-decoration: none; white-space: nowrap; }
    .taqa-request-view:hover { border-color: #306faa; color: #306faa; }
    .taqa-request-date { white-space: nowrap; }
    .taqa-requests-footer { display: flex; flex-wrap: wrap; align-items: center; justify-content: space-between; gap: 16px; min-height: 50px; padding: 12px 22px; background: #fff; border-top: 1px solid #d9e4ef; color: #7b8da0; font-size: 12px; }
    .taqa-requests-footer nav { margin: 0; }
    .taqa-requests-pagination { display: flex; align-items: center; flex-wrap: wrap; gap: 6px; }
    .taqa-requests-pagination a, .taqa-requests-pagination span { display: inline-flex; align-items: center; justify-content: center; min-width: 34px; min-height: 34px; padding: 5px 10px; border: 1px solid #d9e4ef; border-radius: 6px; color: #35526e; text-decoration: none; }
    .taqa-requests-pagination [aria-current="page"] { border-color: #306faa; background: #306faa; color: #fff; }
    .taqa-requests-empty { padding: 48px 20px; text-align: center; color: #7b8da0; }
    .taqa-requests-empty i { margin-bottom: 14px; font-size: 32px; }
    .taqa-requests-empty p { margin: 0; }
    .taqa-requests-error { margin: 0 22px 16px; color: #b54747; font-size: 14px; }
    .taqa-requests-results[aria-busy="true"] { opacity: .6; }
    @media (max-width: 700px) {
        .taqa-requests-wrap { width: 100%; }
        .taqa-requests-heading { flex-wrap: wrap; }
        .taqa-requests-heading h1 { font-size: 24px; }
        .taqa-requests-toolbar { flex-wrap: wrap; padding: 16px; }
        .taqa-requests-search, .taqa-requests-filter { width: 100%; }
        .taqa-requests-footer { padding-inline: 16px; }
    }
</style>

@php
    $statusValue = static fn ($status) => $status instanceof \BackedEnum ? $status->value : (string) $status;
    $statusColor = static fn (string $value) => match ($value) {
        'pending', 'assigned', 'in-progress' => 'is-blue',
        'approved', 'completed', 'paid' => 'is-green',
        'rejected', 'cancelled' => 'is-red',
        'partial', 'partially_paid' => 'is-amber',
        default => '',
    };
@endphp

<div class="taqa-my-requests" dir="{{ in_array(app()->getLocale(), ['ar', 'he', 'fa', 'ur'], true) ? 'rtl' : 'ltr' }}" data-taqa-my-requests>
    <div class="taqa-requests-wrap">
        <header class="taqa-requests-heading">
            <div>
                <a class="taqa-requests-account-link" href="{{ route('account.index') }}" up-follow="false">@lang('ecommerce::ecommerce.my_account')</a>
                <h1>@lang('ecommerce::ecommerce.service_requests')</h1>
                <p>@lang('ecommerce::ecommerce.sr_requests_status_help')</p>
            </div>
            <a class="taqa-requests-new" href="{{ route('service-requests.create') }}" up-follow="false">
                <i class="fa fa-plus" aria-hidden="true"></i>
                @lang('ecommerce::ecommerce.sr_page_new_request')
            </a>
        </header>

        <section class="taqa-requests-card" aria-label="@lang('ecommerce::ecommerce.service_requests')">
            <form class="taqa-requests-toolbar" action="{{ route('account.index') }}" method="GET" data-taqa-requests-filter up-submit="false">
                <input type="hidden" name="section" value="service_requests">
                <div class="taqa-requests-search">
                    <button type="submit" aria-label="@lang('ecommerce::ecommerce.search_request')"><i class="fa fa-search" aria-hidden="true"></i></button>
                    <input type="search" name="search" value="{{ request('search') }}"
                        aria-label="@lang('ecommerce::ecommerce.search_request')"
                        placeholder="@lang('ecommerce::ecommerce.sr_requests_search_placeholder')">
                </div>
                <select class="taqa-requests-filter" name="status" aria-label="@lang('ecommerce::ecommerce.request_status')">
                    <option value="">@lang('ecommerce::ecommerce.sr_requests_all_statuses')</option>
                    @foreach(\App\Application\Educational\Enums\EducationalRequestStatus::cases() as $status)
                        <option value="{{ $status->value }}" @selected(request('status') === $status->value)>{{ $status->trans() }}</option>
                    @endforeach
                </select>
            </form>
            <p class="taqa-requests-error" data-taqa-requests-error role="alert" hidden></p>

            <div class="taqa-requests-results" data-taqa-requests-results aria-live="polite">
                @if($serviceRequests->count())
                    <div class="taqa-requests-scroll">
                        <table class="taqa-requests-table">
                            <thead>
                                <tr>
                                    <th scope="col">@lang('ecommerce::ecommerce.sr_requests_request_service')</th>
                                    <th scope="col">@lang('ecommerce::ecommerce.sr_requests_request')</th>
                                    <th scope="col">@lang('ecommerce::ecommerce.sr_requests_execution')</th>
                                    <th scope="col">@lang('ecommerce::ecommerce.sr_requests_payment')</th>
                                    <th scope="col">@lang('ecommerce::ecommerce.date')</th>
                                    <th scope="col">@lang('ecommerce::ecommerce.sr_requests_action')</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($serviceRequests as $serviceRequest)
                                    @php
                                        $requestStatus = $statusValue($serviceRequest->status);
                                        $executionStatus = $statusValue($serviceRequest->execution_status);
                                        $paymentStatus = $statusValue($serviceRequest->payment_status);
                                        $hasPayment = in_array($paymentStatus, ['paid', 'partial', 'partially_paid'], true);
                                        $notDue = ! $hasPayment && (float) ($serviceRequest->agreed_amount ?? 0) <= 0;
                                        $notStarted = in_array($executionStatus, ['', 'draft', 'pending'], true);
                                        $detailsUrl = route('account.service_request', $serviceRequest->id);
                                    @endphp
                                    <tr>
                                        <td>
                                            <a class="taqa-request-title" href="{{ $detailsUrl }}" up-follow="false">{{ $serviceRequest->title }}</a>
                                            <span class="taqa-request-sub">{{ $serviceRequest->service_type_label }}</span>
                                            <span class="taqa-request-sub"><bdi>#{{ $serviceRequest->id }}</bdi></span>
                                        </td>
                                        <td><span class="taqa-request-pill {{ $statusColor($requestStatus) }}">{{ $serviceRequest->status instanceof \App\Application\Educational\Enums\EducationalRequestStatus ? $serviceRequest->status->trans() : $requestStatus }}</span></td>
                                        <td><span class="taqa-request-pill {{ $notStarted ? '' : $statusColor($executionStatus) }}">{{ $notStarted ? __('ecommerce::ecommerce.not_started') : ($serviceRequest->execution_status instanceof \App\Application\Educational\Enums\EducationalRequestStatus ? $serviceRequest->execution_status->trans() : $executionStatus) }}</span></td>
                                        <td><span class="taqa-request-pill {{ $notDue ? '' : $statusColor($paymentStatus) }}">{{ $notDue ? __('ecommerce::ecommerce.sr_requests_not_due') : ($paymentStatus === 'unpaid' || $paymentStatus === '' ? __('ecommerce::ecommerce.sr_requests_awaiting_payment') : __('ecommerce::ecommerce.payment_status_values.' . $paymentStatus)) }}</span></td>
                                        <td class="taqa-request-date"><time datetime="{{ $serviceRequest->created_at->toDateString() }}">{{ $serviceRequest->created_at->locale(app()->getLocale())->translatedFormat('d F Y') }}</time></td>
                                        <td><a class="taqa-request-view" href="{{ $detailsUrl }}" up-follow="false">@lang('ecommerce::ecommerce.view_details')</a></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="taqa-requests-empty">
                        <i class="fa fa-clipboard" aria-hidden="true"></i>
                        <p>{{ request()->filled('search') || request()->filled('status') ? __('ecommerce::ecommerce.no_matching_service_requests') : __('ecommerce::ecommerce.no_service_requests_yet') }}</p>
                    </div>
                @endif
                <div class="taqa-requests-footer">
                    <span>{{ __('ecommerce::ecommerce.sr_requests_count', ['count' => $serviceRequests->total()]) }}</span>
                    @if($serviceRequests->hasPages())
                        @php($pagination = $serviceRequests->withQueryString())
                        <nav class="taqa-requests-pagination" aria-label="@lang('ecommerce::ecommerce.sr_requests_pages')">
                            @if(! $pagination->onFirstPage())
                                <a href="{{ $pagination->previousPageUrl() }}" rel="prev" up-follow="false">@lang('ecommerce::ecommerce.sr_requests_previous')</a>
                            @endif
                            @foreach($pagination->getUrlRange(max(1, $pagination->currentPage() - 2), min($pagination->lastPage(), $pagination->currentPage() + 2)) as $number => $url)
                                @if($number === $pagination->currentPage())
                                    <span aria-current="page">{{ $number }}</span>
                                @else
                                    <a href="{{ $url }}" up-follow="false">{{ $number }}</a>
                                @endif
                            @endforeach
                            @if($pagination->hasMorePages())
                                <a href="{{ $pagination->nextPageUrl() }}" rel="next" up-follow="false">@lang('ecommerce::ecommerce.sr_requests_next')</a>
                            @endif
                        </nav>
                    @endif
                </div>
            </div>
        </section>
    </div>
</div>

<script>
    (() => {
        const page = document.querySelector('[data-taqa-my-requests]');
        if (!page || page.dataset.initialized) return;
        page.dataset.initialized = 'true';
        const form = page.querySelector('[data-taqa-requests-filter]');
        const error = page.querySelector('[data-taqa-requests-error]');
        let timer;
        let activeRequest;

        async function filterRequests() {
            window.clearTimeout(timer);
            if (activeRequest) activeRequest.abort();
            const controller = new AbortController();
            activeRequest = controller;
            const url = new URL(form.action, window.location.origin);
            new FormData(form).forEach((value, key) => url.searchParams.set(key, value));
            const results = page.querySelector('[data-taqa-requests-results]');
            results.setAttribute('aria-busy', 'true');
            error.hidden = true;
            try {
                const response = await fetch(url, { signal: controller.signal, credentials: 'same-origin', headers: { Accept: 'text/html' } });
                if (response.redirected) {
                    window.location.assign(response.url);
                    return;
                }
                if (!response.ok) throw new Error('Request failed');
                const html = await response.text();
                if (controller.signal.aborted) return;
                const document = new DOMParser().parseFromString(html, 'text/html');
                const updated = document.querySelector('[data-taqa-requests-results]');
                if (!updated) throw new Error('Results missing');
                results.replaceWith(updated);
                window.history.replaceState(window.history.state, '', url);
            } catch (failure) {
                if (failure.name !== 'AbortError' && !controller.signal.aborted) {
                    error.textContent = @json(__('ecommerce::ecommerce.sr_requests_load_error'));
                    error.hidden = false;
                }
            } finally {
                if (activeRequest === controller) {
                    page.querySelector('[data-taqa-requests-results]').removeAttribute('aria-busy');
                    activeRequest = null;
                }
            }
        }

        form.addEventListener('submit', (event) => { event.preventDefault(); filterRequests(); });
        form.querySelector('[name="status"]').addEventListener('change', filterRequests);
        form.querySelector('[name="search"]').addEventListener('input', () => {
            window.clearTimeout(timer);
            if (activeRequest) activeRequest.abort();
            timer = window.setTimeout(filterRequests, 350);
        });
    })();
</script>

@if(request()->hasHeader('X-Up-Target'))
</div>
@endif
