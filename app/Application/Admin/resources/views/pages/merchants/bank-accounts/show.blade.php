@extends('admin::layout.layout')
@section('title', __('admin::home.admin') . ' | ' . __('admin::home.review_bank_account_request'))

@section('content')
    <div class="col-xxl-12 col-xl-12">
        <livewire:admin::merchant.bank-accounts.review :request-id="$bankRequest->id" />
    </div>
@endsection
