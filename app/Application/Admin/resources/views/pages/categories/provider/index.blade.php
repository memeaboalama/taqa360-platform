@extends('admin::layout.layout')
@section('title',"Admin | " . __('admin::lang.provider categories'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.provider categories')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createProviderCategory">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::category.provider.table.all/>
                    </div>
                </div>

                <livewire:admin::category.provider.modal.create/>
                <livewire:admin::category.provider.modal.update/>
                <livewire:admin::category.provider.modal.delete/>
            </div>
        </div>
    </div>

@endsection
