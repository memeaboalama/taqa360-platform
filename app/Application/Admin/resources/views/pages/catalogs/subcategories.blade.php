@extends('admin::layout.layout')
@section('title',"Admin | " . __('admin::catalog.subcategory'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::catalog.subcategory')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createSubcategory">@lang('Add')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::catalog.subcategory.table.all/>
                    </div>
                </div>

                <livewire:admin::catalog.subcategory.modal.create/>
                <livewire:admin::catalog.subcategory.modal.update/>
                <livewire:admin::catalog.subcategory.modal.delete/>
            </div>
        </div>
    </div>

@endsection
