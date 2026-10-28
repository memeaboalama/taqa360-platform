@extends('marketplace::layout.layout')

@section('title',__('marketplace::home.Merchant'). " | " . __('marketplace::home.promocodes'))
@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.promocodes')
                    </div>
                    {{-- @AdminHasRole(['create_coupons']) --}}
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createMerchantPromocode">{{__('marketplace::home.Add New')}}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    {{-- @endAdminHasRole --}}
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:marketplace::promocode.table.all/>
                    </div>
                </div>

                {{-- @AdminHasRole(['create_coupons']) --}}
                <livewire:marketplace::promocode.modal.create/>
                {{-- @endAdminHasRole --}}

                {{-- @AdminHasRole(['edit_coupons']) --}}
                <livewire:marketplace::promocode.modal.update/>
                {{-- @endAdminHasRole --}}

                {{-- @AdminHasRole(['delete_coupons']) --}}
                <livewire:marketplace::promocode.modal.delete/>
                {{-- @endAdminHasRole --}}


            </div>
        </div>
    </div>

@endsection
