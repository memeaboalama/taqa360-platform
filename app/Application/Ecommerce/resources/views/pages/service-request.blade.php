@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.request_service'))

@section('active-nav', 'home')

@section('content')
    @if (request()->filled('service'))
        @include('ecommerce::partials.service-request-form')
    @else
        @include('ecommerce::partials.service-request-catalog')
    @endif
@endsection
