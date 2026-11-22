@extends('ecommerce::layout.store-layout')

@section('title', __('ecommerce::ecommerce.sr_catalog_title'))

@section('active-nav', 'services')

@section('content')
    @include('ecommerce::partials.service-request-catalog')
@endsection
