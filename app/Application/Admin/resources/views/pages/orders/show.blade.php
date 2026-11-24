@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.showOrder'))

@include('marketplace::pages.orders.partials.styles')

@section('content')
    <div id="merchant-order-show">
        <livewire:admin::order.table.details :order="$order" :orderServices="$order->services" :orderUser="$orderUser" :address="$address" />
    </div>
@endsection
