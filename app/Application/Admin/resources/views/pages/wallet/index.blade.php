@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.walletcoupons'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.walletcoupons')
                    </div>
                    @AdminHasRole(['create_wallet coupons'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createWalletCoupon">{{__('admin::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::wallet.table.all/>
                    </div>
                </div>

                @AdminHasRole(['create_wallet coupons'])
                <livewire:admin::wallet.modal.coupon.create/>
                @endAdminHasRole

                @AdminHasRole(['edit_wallet coupons'])
                <livewire:admin::wallet.modal.coupon.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_wallet coupons'])
                <livewire:admin::wallet.modal.coupon.delete/>
                @endAdminHasRole


            </div>
        </div>
    </div>

@endsection
