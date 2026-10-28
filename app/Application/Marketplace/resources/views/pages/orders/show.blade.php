@extends('marketplace::layout.layout')
@section('title',"تفاصيل الطلب #" . $order->id)

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <livewire:marketplace::order.table.read :order="$order" :orderServices="$orderServices" :orderUser="$orderUser" :address="$address" />
        </div>
    </div>
@endsection
