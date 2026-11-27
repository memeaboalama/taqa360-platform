@extends('ecommerce::layout.store-layout')

@section('title', app()->getLocale() === 'ar' ? 'تم استلام طلب الخدمة بنجاح' : 'Service Request Received')

@section('active-nav', 'services')

@section('page-styles')
    <style>
        .service-success {
            min-height: 48vh;
            padding: 52px 0 68px;
            background: var(--soft);
        }

        .service-success-panel {
            width: min(100%, 680px);
            margin: 0 auto;
            padding: 38px 32px;
            border: 1px solid var(--line);
            border-radius: 12px;
            background: #fff;
            box-shadow: var(--shadow);
            text-align: center;
        }

        .service-success-icon {
            display: grid;
            place-items: center;
            width: 68px;
            height: 68px;
            margin: 0 auto 18px;
            border: 1px solid #bde8cd;
            border-radius: 50%;
            background: #effaf3;
            color: #23834b;
            font-size: 28px;
        }

        .service-success-panel h1 {
            margin: 0 0 10px;
            color: var(--ink);
            font-size: 25px;
            font-weight: 800;
        }

        .service-success-panel p {
            margin: 0;
            color: var(--muted);
            font-size: 14px;
            line-height: 1.9;
        }

        .service-confirmation-details {
            display: grid;
            grid-template-columns: minmax(0, 1fr) minmax(0, 1fr);
            gap: 12px;
            margin: 24px 0 0;
            padding: 18px;
            border: 1px solid var(--line);
            border-radius: 8px;
            background: #fafbfc;
            text-align: start;
        }

        .service-confirmation-details div {
            min-width: 0;
        }

        .service-confirmation-details dt {
            color: var(--muted);
            font-size: 12px;
        }

        .service-confirmation-details dd {
            margin: 3px 0 0;
            color: var(--ink);
            font-weight: 700;
            overflow-wrap: anywhere;
        }

        .service-success-actions {
            display: flex;
            justify-content: center;
            flex-wrap: wrap;
            gap: 10px;
            margin-top: 26px;
        }

        .service-success-actions a {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            min-height: 42px;
            padding: 9px 17px;
            border: 1px solid var(--line);
            border-radius: 6px;
            font-weight: 700;
            text-decoration: none;
            transition: background .2s ease, border-color .2s ease;
        }

        .service-success-actions .success-primary {
            border-color: var(--yellow);
            background: var(--yellow);
            color: #fff;
        }

        .service-success-actions .success-primary:visited,
        .service-success-actions .success-primary:hover,
        .service-success-actions .success-primary:focus,
        .service-success-actions .success-primary:active {
            color: #fff;
            text-decoration: none;
        }

        .service-success-actions .success-primary:hover {
            border-color: var(--yellow-deep);
            background: var(--yellow-deep);
        }

        .service-success-actions .success-secondary {
            background: #fff;
            color: var(--ink);
        }

        .service-success-actions .success-secondary:hover {
            border-color: var(--yellow);
            background: #f6f7f9;
        }

        @media(max-width: 520px) {
            .service-success {
                padding: 32px 0 46px;
            }

            .service-success-panel {
                padding: 30px 20px;
            }

            .service-success-panel h1 {
                font-size: 21px;
            }

            .service-confirmation-details {
                grid-template-columns: 1fr;
            }

            .service-success-actions {
                flex-direction: column;
            }
        }
    </style>
@endsection

@section('content')
    @php
        $isAr = app()->getLocale() === 'ar';
        $statusEnum = $serviceRequest->status instanceof \App\Application\Educational\Enums\EducationalRequestStatus
            ? $serviceRequest->status
            : \App\Application\Educational\Enums\EducationalRequestStatus::tryFrom((string) $serviceRequest->status);
        $statusText = $statusEnum?->trans() ?? (string) $serviceRequest->status;
    @endphp
    <main class="service-success">
        <div class="container">
            <section class="service-success-panel" role="status" aria-live="polite">
                <div class="service-success-icon" aria-hidden="true">
                    <i class="fa fa-check"></i>
                </div>
                <h1>{{ $isAr ? 'شكراً لك! تم استلام طلب الخدمة بنجاح' : 'Thank You! Service Request Received' }}</h1>
                <p>
                    {{ $isAr
                        ? 'تم تسجيل طلبك رقم #' . $serviceRequest->id . ' بنجاح، وسيتم مراجعته من قبل فريقنا والتواصل معك في أقرب وقت.'
                        : 'Your service request #' . $serviceRequest->id . ' has been submitted successfully. Our team will review it and get back to you shortly.' }}
                </p>

                <dl class="service-confirmation-details">
                    <div>
                        <dt>{{ $isAr ? 'رقم الطلب' : 'Request Number' }}</dt>
                        <dd>#{{ $serviceRequest->id }}</dd>
                    </div>
                    <div>
                        <dt>{{ $isAr ? 'تاريخ الطلب' : 'Request Date' }}</dt>
                        <dd>{{ $serviceRequest->created_at?->format('Y-m-d H:i') ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>{{ $isAr ? 'الخدمة المطلوبة' : 'Requested Service' }}</dt>
                        <dd>{{ $serviceRequest->service?->title ?? '—' }}</dd>
                    </div>
                    <div>
                        <dt>{{ $isAr ? 'حالة الطلب' : 'Status' }}</dt>
                        <dd>{{ $statusText }}</dd>
                    </div>
                    <div style="grid-column: 1 / -1;">
                        <dt>{{ $isAr ? 'عنوان الطلب' : 'Request Title' }}</dt>
                        <dd>{{ $serviceRequest->title }}</dd>
                    </div>
                </dl>

                <div class="service-success-actions">
                    <a class="success-primary" href="{{ route('account.service_request', $serviceRequest->id) }}">
                        {{ $isAr ? 'عرض تفاصيل الطلب' : 'View Request Details' }}
                    </a>
                    <a class="success-secondary" href="{{ route('account.service_requests') }}">
                        {{ $isAr ? 'جميع طلبات الخدمات' : 'My Service Requests' }}
                    </a>
                    <a class="success-secondary" href="{{ route('ecommerce.static') }}">
                        {{ $isAr ? 'العودة للرئيسية' : 'Back to Home' }}
                    </a>
                </div>
            </section>
        </div>
    </main>
@endsection
