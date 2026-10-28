@extends('admin::layout.layout')
@section('title',"Admin | " . __('admin::catalog.child_categories'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::catalog.child_categories')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createChildCategory">@lang('Add')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::catalog.child-category.table.all/>
                    </div>
                </div>

                <livewire:admin::catalog.child-category.modal.create/>
                <livewire:admin::catalog.child-category.modal.update/>
                <livewire:admin::catalog.child-category.modal.delete/>
            </div>
        </div>
    </div>

@endsection
