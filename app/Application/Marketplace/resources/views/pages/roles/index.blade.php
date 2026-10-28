@extends('marketplace::layout.layout')

@section('title',__('marketplace::home.Merchant |Roles'))
@section('content')
    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('marketplace::home.roles')
                    </div>
                    @MerchantHasRole(['create_roles'])
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createRole">{{ __('marketplace::home.Add New') }}<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                    @endMerchantHasRole
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:marketplace::role.table.all/>
                    </div>
                </div>
                @MerchantHasRole(['create_roles'])
                <livewire:marketplace::role.modal.create/>
                @endMerchantHasRole
                <livewire:marketplace::role.modal.update/>
            </div>
        </div>

    </div>
@endsection
