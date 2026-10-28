@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('marketplace::home.showCompany'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <livewire:admin::company.tabs.orders.show :order_id="$id" :company_id="$company_id"/>
        </div>
    </div>
@endsection
