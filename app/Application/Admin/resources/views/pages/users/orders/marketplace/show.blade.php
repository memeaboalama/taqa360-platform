@extends('admin::layout.layout')
@section('title',"Customer Order |" . __('marketplace::home.show'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <livewire:admin::user.tabs.orders.marketplace.show :order_id="$id" :user_id="$user_id"/>
        </div>
    </div>
@endsection
