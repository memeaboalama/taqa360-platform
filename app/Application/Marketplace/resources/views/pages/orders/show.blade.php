@extends('marketplace::layout.layout')
@section('title',"تفاصيل الطلب #" . $order->id)

@include('marketplace::pages.orders.partials.styles')

@section('content')
    <div id="merchant-order-show">
        <livewire:marketplace::order.table.read :order="$order" :orderServices="$orderServices" :orderUser="$orderUser" :address="$address" />
    </div>
@endsection
