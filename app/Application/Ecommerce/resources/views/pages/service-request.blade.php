@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.request_service'))

@section('active-nav', 'home')

@section('content')
    @if (config('app.brand') === 'taqa360')
        @include('ecommerce::partials.taqa-service-request-form')
    @elseif (request()->filled('service'))
        @include('ecommerce::partials.service-request-form')
    @else
        @include('ecommerce::partials.service-request-catalog')
    @endif
@endsection
