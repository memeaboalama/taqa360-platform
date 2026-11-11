@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Refundpayment'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::refund-payment.table.filter/>
                    </div>
                    @AdminHasRole(['create_refundpayments', 'show_roles'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createRefundPayment">{{ __('admin::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @AdminHasRole(['show_refundpayments', 'show_roles'])
                            <livewire:admin::refund-payment.table.all/>
                        @endAdminHasRole
                    </div>
                </div>

                @AdminHasRole(['create_refundpayments', 'show_roles'])
                    <livewire:admin::refund-payment.modal.create/>
                @endAdminHasRole
            </div>
        </div>
    </div>


@endsection
