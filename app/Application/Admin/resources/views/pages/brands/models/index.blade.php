@extends('admin::layout.layout')
@section('title',"Admin |" . __('admin::lang.Models'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.Models')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createModel">Add New<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::brand.model.table.all/>
                    </div>
                </div>

                <livewire:admin::brand.model.modal.create/>
                <livewire:admin::brand.model.modal.update/>
                <livewire:admin::brand.model.modal.delete/>
            </div>
        </div>
    </div>

@endsection
