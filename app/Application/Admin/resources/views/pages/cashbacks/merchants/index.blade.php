@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Cash back'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Merchants Cashback')
                    </div>
                    <!-- <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createCashbackAdminMerchants">{{__('admin::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div> -->

                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @AdminHasRole(['show_merchant cashbacks', 'show_coupons'])
                            <livewire:admin::cash-back.table.merchants.all/>
                        @endAdminHasRole
                    </div>
                </div>

                @AdminHasRole(['edit_merchant cashbacks', 'edit_merchant coupons', 'edit_coupons'])
                    <livewire:admin::cash-back.modal.merchants.update/>
                @endAdminHasRole
                @AdminHasRole(['delete_merchant cashbacks', 'delete_merchant coupons', 'delete_coupons'])
                    <livewire:admin::cash-back.modal.merchants.delete/>
                @endAdminHasRole
                


            </div>
        </div>
    </div>

@endsection
