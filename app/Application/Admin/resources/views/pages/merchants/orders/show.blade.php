@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " .  __('marketplace::home.show'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <livewire:admin::merchant.tabs.orders.show :order_id="$id" :merchant_id="$merchant_id"/>
        </div>
    </div>
@endsection
