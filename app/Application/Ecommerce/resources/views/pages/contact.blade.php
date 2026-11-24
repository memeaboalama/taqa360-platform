@extends('ecommerce::layout.store-layout')

@php
    $contactInfo = data_get($footerData, 'contact', []);
    $contactPhone = data_get($contactInfo, 'phone');
    $contactEmail = data_get($contactInfo, 'email');
    $contactAddress = data_get($contactInfo, 'address.' . $currentLocale)
        ?? data_get($contactInfo, 'address.en')
        ?? data_get($contactInfo, 'address.ar');
@endphp

@section('title', $pageTitle)

@section('page-styles')
    <style>
        .contact-page {
            width: min(1000px, calc(100% - 40px));
            margin: 40px auto 64px;
            padding: 32px;
            border: 1px solid #e9ebef;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(29, 37, 48, .055);
        }

        .contact-page h1 {
            margin: 0 0 24px;
            font-size: 26px;
        }

        .contact-details {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 16px;
        }

        .contact-detail {
            padding: 18px;
            border: 1px solid #e9ebef;
            border-radius: 10px;
        }

        .contact-detail h2 {
            margin: 0 0 8px;
            font-size: 15px;
        }

        .contact-detail p,
        .contact-detail a {
            margin: 0;
            color: #4b5563;
            font-size: 14px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .contact-detail a {
            color: #306faa;
            text-decoration: underline;
        }

        .contact-social-section {
            margin-top: 32px;
        }

        .contact-social-section h2 {
            margin: 0 0 14px;
            font-size: 18px;
        }

        .contact-socials {
            flex-wrap: wrap;
            gap: 12px;
        }

        .contact-socials a {
            display: grid;
            width: 44px;
            height: 44px;
            place-items: center;
            border: 1px solid #dce5ef;
            border-radius: 50%;
            background: #f3f7fb;
            color: #306faa;
            font-size: 18px;
        }

        .contact-socials a:hover {
            background: #306faa;
            color: #fff;
        }

        @media (max-width: 600px) {
            .contact-page {
                width: calc(100% - 24px);
                margin: 24px auto 40px;
                padding: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <section class="contact-page" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">
        <h1>{{ $pageTitle }}</h1>
        @if(filled($contactPhone) || filled($contactEmail) || filled($contactAddress))
            <div class="contact-details">
                @if(filled($contactPhone))
                    <div class="contact-detail">
                        <h2>@lang('ecommerce::ecommerce.phone')</h2>
                        <a href="tel:{{ preg_replace('/[^0-9+]/', '', $contactPhone) }}" dir="ltr">{{ $contactPhone }}</a>
                    </div>
                @endif
                @if(filled($contactEmail))
                    <div class="contact-detail">
                        <h2>@lang('ecommerce::ecommerce.email')</h2>
                        <a href="mailto:{{ $contactEmail }}" dir="ltr">{{ $contactEmail }}</a>
                    </div>
                @endif
                @if(filled($contactAddress))
                    <div class="contact-detail">
                        <h2>@lang('ecommerce::ecommerce.address')</h2>
                        <p>{{ $contactAddress }}</p>
                    </div>
                @endif
            </div>
        @else
            <p>@lang('ecommerce::ecommerce.store_contact_unavailable')</p>
        @endif

        @include('ecommerce::partials.social-links', [
            'socialContainerClass' => 'socials contact-socials',
            'socialSectionTitle' => __('ecommerce::ecommerce.follow_us'),
        ])
    </section>
@endsection
