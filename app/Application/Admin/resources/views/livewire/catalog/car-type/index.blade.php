@extends('admin::layout.layout')
@section('title', "Admin | " . __('admin::lang.Car Types'))

@section('content')

    <div class="row">
        <div class="col-xl-12">
            <div class="card custom-card">
                <div class="card-header justify-content-between">
                    <div class="card-title">
                        @lang('admin::lang.Car Types')
                    </div>
                    <div class="prism-toggle">
                        <button
                            class="btn btn-primary-light m-1"
                            onclick="Livewire.emit('openCreateCarTypeModal')">@lang('admin::lang.Add New')<i class="bi bi-plus-lg ms-2"></i></button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        @livewire('admin::catalog.car-type.table.all')
                    </div>
                </div>

                @livewire('admin::catalog.car-type.modal.create')
                @livewire('admin::catalog.car-type.modal.update')
                @livewire('admin::catalog.car-type.modal.delete')
            </div>
        </div>
    </div>

@endsection
