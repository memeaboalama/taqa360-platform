@extends('ecommerce::layout.store-layout')

@section('title', $pageTitle)

@section('page-styles')
    <style>
        .legal-page {
            width: min(1000px, calc(100% - 40px));
            margin: 40px auto 64px;
            padding: 32px;
            border: 1px solid #e9ebef;
            border-radius: 12px;
            box-shadow: 0 5px 18px rgba(29, 37, 48, .055);
        }

        .legal-page h1 {
            margin: 0 0 24px;
            font-size: 26px;
        }

        .legal-page-content {
            color: #4b5563;
            font-size: 15px;
            line-height: 1.9;
        }

        @media (max-width: 600px) {
            .legal-page {
                width: calc(100% - 24px);
                margin: 24px auto 40px;
                padding: 20px;
            }
        }
    </style>
@endsection

@section('content')
    <article class="legal-page">
        <h1>{{ $pageTitle }}</h1>
        <div class="legal-page-content" dir="{{ $isRTL ? 'rtl' : 'ltr' }}">
            @if(filled($content))
                {!! $content !!}
            @else
                <p>@lang('ecommerce::ecommerce.legal_content_unavailable')</p>
            @endif
        </div>
    </article>
@endsection
