@extends('admin::layout.layout')
@section('title',"Admin | " . __('admin::catalog.categories'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::catalog.categories')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createCatalogCategory">@lang('Add')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::catalog.category.table.all/>
                    </div>
                </div>

                <livewire:admin::catalog.category.modal.create/>
                <livewire:admin::catalog.category.modal.update/>
                <livewire:admin::catalog.category.modal.delete/>
            </div>
        </div>
    </div>

@endsection
