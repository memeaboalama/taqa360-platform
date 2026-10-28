@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.promocodes'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Merchants Promocodes')
                    </div>
                    @AdminHasRole(['create_merchant coupons'])
                    {{-- <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createPromocodeAdminMerchants">{{__('admin::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div> --}}
                    @endAdminHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::promocode.table.merchants.all/>
                    </div>
                </div>

                @AdminHasRole(['create_merchant coupons'])
                {{-- <livewire:admin::promocode.modal.merchants.create/> --}}
                @endAdminHasRole

                @AdminHasRole(['edit_merchant coupons'])
                <livewire:admin::promocode.modal.merchants.update/>
                @endAdminHasRole

                @AdminHasRole(['delete_merchant coupons'])
                <livewire:admin::promocode.modal.merchants.delete/>
                @endAdminHasRole


            </div>
        </div>
    </div>

@endsection
