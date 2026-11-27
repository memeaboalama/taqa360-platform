@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . ' | ' . __('admin::home.bank_account_requests'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <livewire:admin::merchant.bank-accounts.index />
    </div>
@endsection
