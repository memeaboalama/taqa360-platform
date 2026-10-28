@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.showOrder'))

<style type="text/css">
    @page {
        size: auto;   /* auto is the initial value */
        margin: 0;  /* this affects the margin in the printer settings */
    }
</style>
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <livewire:admin::order.table.read :order="$order" :orderItems="$orderItems" :orderUser="$orderUser" :address="$address" :driver="$driver" :payment="$payment"/>
        </div>
    </div>
@endsection
