@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Merchant Packages'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::home.Merchant Packages')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createPackage">@lang('admin::lang.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::package.table.all/>
                    </div>
                </div>

                <livewire:admin::package.modal.create/>
                <livewire:admin::package.modal.update/>
                <livewire:admin::package.modal.delete/>
            </div>
        </div>
    </div>

@endsection
