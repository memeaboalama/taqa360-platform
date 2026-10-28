@extends('marketplace::layout.layout')

@section('title',__('marketplace::home.Merchant'). " | " . __('admin::home.Cash back'))
@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Cash back')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createMerchantCashback">{{__('marketplace::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:marketplace::cashback.table.all/>
                    </div>
                </div>
                <livewire:marketplace::cashback.modal.create/>               
                <livewire:marketplace::cashback.modal.update/>
                <livewire:marketplace::cashback.modal.delete/>
            </div>
        </div>
    </div>

@endsection
