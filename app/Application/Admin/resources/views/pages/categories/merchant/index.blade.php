@extends('admin::layout.layout')
@section('title',"Admin | " . __('admin::lang.merchant categories'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.merchant categories')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createMerchantCategory">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::category.merchant.table.all/>
                    </div>
                </div>

                <livewire:admin::category.merchant.modal.create/>
                <livewire:admin::category.merchant.modal.update/>
                <livewire:admin::category.merchant.modal.delete/>
            </div>
        </div>
    </div>

@endsection
