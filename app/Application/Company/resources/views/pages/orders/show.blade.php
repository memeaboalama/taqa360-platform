@extends('company::layout.layout')
@section('title',"Company |" . __('company::home.orders'))

@section('content')
    <div class="row">
        <div class="col-xl-12">
            <livewire:company::order.table.show :order="$order" :towingOrderDetails="$towingOrderDetails" :provider_sub_services="$provider_sub_services" :provider="$provider" />
        </div>
    </div>
@endsection
