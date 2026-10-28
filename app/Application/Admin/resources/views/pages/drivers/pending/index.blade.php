@extends('admin::layout.layout')
@section('title',__('admin::home.admin') . " | " . __('admin::home.Pending Drivers'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="row mb-0">
                        <livewire:admin::driver.table.filter/>
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            data-bs-toggle="modal"
                            data-bs-target="#createDriver">@lang('admin::home.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <livewire:admin::driver.pending.table.all/>
                    </div>
                </div>

                <livewire:admin::driver.modal.create/>
                <livewire:admin::driver.modal.delete/>
                <livewire:admin::driver.modal.approve/>
                <livewire:admin::driver.modal.reject/>
                <livewire:admin::driver.modal.reset-password/>
                <livewire:admin::driver.modal.history/>
            </div>
        </div>
    </div>

@endsection
